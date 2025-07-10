<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartRequest;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    /**
     * Inject CartService into the controller.
     */
    public function __construct(CartService $cartService)
    {
        $this->middleware('auth');
        $this->cartService = $cartService;
    }

    /**
     * Show full cart page.
     */
    public function index()
    {
        $cartItems = $this->cartService->getUserCart();
        $total = $this->cartService->getTotal();
        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Add item to cart (for AJAX or regular POST).
     */
    public function store(StoreCartRequest $request)
    {
        try{
        $validated = $request->validated();
        $this->cartService->addToCart(
            $validated['product_id'],
            $validated['quantity'],
            $request->input('size') // optional
        );

            return response()->json(['success'=>true,'message' => 'Added to cart successfully.']);

} catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
    }

    /**
     * Remove a cart item.
     */
    public function destroy($id)
    {
        $this->cartService->removeFromCart($id);
        return redirect()->back()->with('success', 'Item removed from cart.');
    }

    /**
     * Show cart dropdown preview (partial for navbar).
     */
    public function dropdownPreview()
    {
        $data = $this->cartService->getDropdownPreview();
        return view('components.cart-dropdown', $data);
    }

    /**
     * Clear the entire cart.
     */
    public function clear()
    {
        $this->cartService->clearCart();
        return redirect()->back()->with('success', 'Cart cleared.');
    }
}
