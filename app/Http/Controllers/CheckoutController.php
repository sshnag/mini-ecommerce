<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;


class CheckoutController extends Controller
{
    public function showShipping() {
  return view('checkout.shipping');
}

public function storeShipping(Request $request) {
    $data = $request->validate([
        'street' => 'required|string|max:255',
        'city' => 'required|string|max:100',
        'postal_code' => 'required|string|max:20',
        'country' => 'required|string|max:100',
        'payment_method' => 'required|in:paypal,card,cod',
    ]);

    $paymentMethod = $data['payment_method'];
    unset($data['payment_method']);

    $data['user_id'] = Auth::id();

    try {
        $address = Address::create($data);

        session([
            'checkout_address_id' => $address['id'],
            'checkout_payment_method' => $paymentMethod,
        ]);

        return redirect()->route('checkout.review')
            ->with('success', 'Shipping address saved successfully');

    } catch (\Exception $e) {
        return back()->with('error', 'Failed to save address: ' . $e->getMessage());
    }
}

public function showReview(CartService $cartService) {
    $addressId = session('checkout_address_id');

    if (!$addressId) {
        return redirect()->route('checkout.shipping')
            ->with('error', 'Please provide a shipping address first');
    }

    $address = Address::find($addressId);

    if (!$address) {
        return redirect()->route('checkout.shipping')
            ->with('error', 'Invalid shipping address. Please provide a new one');
    }

    $paymentMethod = session('checkout_payment_method');
    $cartItems = $cartService->getUserCart();
    $total = $cartService->getTotal();

    return view('checkout.review', compact('cartItems', 'total', 'address', 'paymentMethod'));
}

public function placeOrder(CartService $cs) {
    $addressId = session('checkout_address_id');

    if (!$addressId) {
        return redirect()->route('checkout.review')
            ->with('error', 'Shipping address is required');
    }

    $address = Address::find($addressId);

    if (!$address) {
        return redirect()->route('checkout.review')
            ->with('error', 'Invalid shipping address');
    }
    try {
        $order=$cs->placeOrder(Auth::id(),$addressId);
        if (!$order) {
            return redirect()->route('checkout.review')->with('error','Unable to place order.Your cart might be empty.');
        }
        session()->forget(['checkout_address_id','checkout_payment_method']);
        return redirect()->route('user.orders.confirmation',['order'=>$order['id']])->with('success','Order placed successfully!');
    } catch (\Exception $e) {
        return redirect()->route('checkout.review')->with('error',$e->getMessage());
    }

}

}
