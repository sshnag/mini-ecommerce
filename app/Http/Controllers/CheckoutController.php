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
    unset($data['payment_method']); // remove so Address doesn't try to save it

    $data['user_id'] = Auth::id();

    $address = Address::create($data);

    session([
        'checkout_address_id' => $address->id,
        'checkout_payment_method' => $paymentMethod,  // save payment method in session
    ]);

    return redirect()->route('checkout.review');
}


public function showReview(CartService $cartService) {
    $address = Address::find(session('checkout_address_id'));
    $paymentMethod = session('checkout_payment_method');
    $cartItems = $cartService->getUserCart();
    $total = $cartService->getTotal();

    return view('checkout.review', compact('cartItems', 'total', 'address', 'paymentMethod'));
}

public function placeOrder(CartService $cs) {
    $order = $cs->placeOrder(Auth::id(), session('checkout_address_id'));

    if (!$order) {
        return redirect()->route('checkout.review')->with('error', 'Unable to place order. Your cart might be empty.');
    }

    // Clear session data
    session()->forget('checkout_address_id');
    session()->forget('checkout_payment_method');

    // Redirect to the order details page with the order id
    return redirect()->route('orders.show', ['order' => $order->id])
                     ->with('success', 'Order placed successfully!');
}

}
