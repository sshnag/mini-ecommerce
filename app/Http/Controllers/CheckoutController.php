<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;
use App\Services\CartService;
use App\Services\StockValidationService;
use Exception;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    protected $stockValidationService;

    public function __construct(StockValidationService $stockValidationService)
    {
        $this->stockValidationService = $stockValidationService;
    }

    /**
     * Summary of showShipping
     * Show the shipping form.
     * @return \Illuminate\Contracts\View\View
     */
    public function showShipping()
    {
        return view('checkout.shipping');
    }

    /**
     * Summary of storeShipping
     * Store shipping info and payment method in session.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeShipping(Request $request)
    {
        $validated = $request->validate([
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'payment_method' => 'required|in:paypal,card,cod',
        ]);

        $paymentMethod = $validated['payment_method'];
        //destroy payment method
        unset($validated['payment_method']);

        $validated['user_id'] = Auth::id();

        try {
            $address = Address::create($validated);

            session([
                'checkout_address_id' => $address['id'],
                'checkout_payment_method' => $paymentMethod,
            ]);

            return redirect()->route('checkout.review')->with('success', 'Shipping info saved.');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to save shipping info: ' . $e->getMessage());
        }
    }

    /**
     * Summary of showReview
     * Show the order review page with stock validation.
     * @param \App\Services\CartService $cartService
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showReview(CartService $cartService)
    {
        $addressId = session('checkout_address_id');
        $paymentMethod = session('checkout_payment_method');

        if (!$addressId || !$paymentMethod) {
            return redirect()->route('checkout.shipping')
                ->with('error', 'Please complete shipping and payment information first.');
        }

        $address = Address::find($addressId);

        if (!$address) {
            return redirect()->route('checkout.shipping')->with('error', 'Invalid shipping address.');
        }

        // Validate stock before showing review
        $stockValidation = $this->stockValidationService->validateCartStock();
        
        if ($stockValidation['has_issues']) {
            return redirect()->route('cart.index')
                ->with('stock_errors', $stockValidation)
                ->with('error', 'Some items in your cart are out of stock or have insufficient quantity.');
        }

        $cartItems = $cartService->getUserCart();
        $total = $cartService->getTotal();

        return view('checkout.review', compact('cartItems', 'total', 'address', 'paymentMethod'));
    }

    /**
     * Summary of placeOrder
     * Place the order with stock validation and reservation.
     * @param \Illuminate\Http\Request $request
     * @param \App\Services\CartService $cs
     * @return \Illuminate\Http\RedirectResponse
     */
    public function placeOrder(Request $request, CartService $cs)
    {
        $addressId = session('checkout_address_id');
        if (!$addressId) {
            return redirect()->route('checkout.review')->with('error', 'Shipping address is missing.');
        }

        $address = Address::find($addressId);
        if (!$address) {
            return redirect()->route('checkout.review')->with('error', 'Invalid address.');
        }

        try {
            // Final stock validation before placing order
            $stockValidation = $this->stockValidationService->validateCartStock();
            
            if ($stockValidation['has_issues']) {
                return redirect()->route('cart.index')
                    ->with('stock_errors', $stockValidation)
                    ->with('error', 'Some items in your cart are out of stock or have insufficient quantity.');
            }

            $order = $cs->placeOrder(Auth::id(), $addressId);

            if (!$order) {
                return redirect()->route('checkout.review')->with('error', 'Unable to place order. Your cart might be empty.');
            }

            // Clear session to avoid re-submitting order
            session()->forget(['checkout_address_id', 'checkout_payment_method']);

            //  Redirect to confirmation page
            return redirect()->route('orders.confirmation', ['order' => $order['id']])
                ->with('success', 'Order placed successfully!');
        } catch (Exception $e) {
            return redirect()->route('checkout.review')->with('error', 'Order failed: ' . $e->getMessage());
        }
    }

    /**
     * Check stock availability via AJAX
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkStock(Request $request)
    {
        $stockValidation = $this->stockValidationService->validateCartStock();
        
        return response()->json([
            'has_issues' => $stockValidation['has_issues'],
            'out_of_stock' => $stockValidation['out_of_stock'],
            'low_stock' => $stockValidation['low_stock']
        ]);
    }
}
