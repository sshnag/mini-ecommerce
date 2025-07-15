<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Address;
use Illuminate\Http\Request;
use App\Services\CartService;
use App\Models\User;
use App\Http\Controllers\CartController;
use App\Models\OrderItem;

class OrderController extends Controller
{
    /**
     * Summary of index
     * displaying orders's list
     * @return \Illuminate\Contracts\View\View
     */
   public function index()
{
    $status = request()->input('status');

    $orders = Order::with(['user', 'orderItems.product'])
        ->when($status && $status !== 'all', function($query) use ($status) {
            return $query->where('status', $status);
        })
        ->latest()
        ->paginate(10);

    // Use different view for admin vs user


    return view('admin.orders.index', compact('orders')); // regular user
}
public function updateStatus(Request $request, Order $order)
{
    $validated = $request->validate([
        'status' => 'required|in:pending,processing,completed,cancelled'
    ]);

    $order->update($validated);

    return back()->with('success', 'Order status updated');
}
    /**
     * Summary of show
     * displaying orderitems
     * @param \App\Models\Order $order
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Order $order)
{
    $order->load(['user', 'orderItems.product', 'address']);
    return view('admin.orders.show', compact('order'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Summary of store
     * storing orders' data
     * @param \App\Http\Requests\StoreOrderRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreOrderRequest $request)
    {
        //
        $validated=$request->validated();
        $validated['user_id']=Auth::id();
        $order=Order::create($validated);
        $cartItems=Cart::with('products')->where('user_id',Auth::id())->get();
        foreach($cartItems as $item){
            OrderItem::create([
            'order_id'=>$order->id,
            'product_id'=>$item->product_id,
            'quantity'=>$item->quantity,
            'price'=>$item->price,
        ]);
        $item->product->decrement('stock',$item->quantity);

        }
        Cart::where('user_id',Auth::id())->delete();
        return redirect()->route('orders.index')->with('success','Order has been placed');

    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
        $order->delete();
        return back()->with('success','Order is archieved');
    }
    public function showShippingForm()
{
    $user = Auth::user();
    return view('checkout.shipping', compact('user'));
}

public function storeShipping(Request $request)
{
    $data = $request->validate([
        'street' => 'required|string|max:255',
        'city' => 'required|string|max:100',
        'postal_code' => 'required|string|max:20',
        'country' => 'required|string|max:100',
        'payment_method' => 'required|string|in:paypal,card,cod',
    ]);

    $userId = Auth::id();

    $address = Address::create([
        'user_id'     => $userId,
        'street'      => $data['street'],
        'city'        => $data['city'],
        'postal_code' => $data['postal_code'],
        'country'     => $data['country'],
    ]);

    session(['checkout_address_id' => $address->id]);
    session(['checkout_payment_method' => $data['payment_method']]);

    return redirect()->route('checkout.review');
}

public function showReview(CartService $cartService)
{
    $cartItems = $cartService->getUserCart();
    $total = $cartService->getTotal();

    $address = Address::where('user_id', Auth::id())
                      ->find(session('checkout_address_id'));

    return view('checkout.review', compact('cartItems', 'total', 'address'));
}

public function placeOrder(CartService $cartService)
{
    $user = Auth::user();
    $paymentMethod = session('checkout_payment_method');

    $order = Order::create([
        'user_id' => $user->id,
        'address_id' => session('checkout_address_id'),
        'total_amount' => $cartService->getTotal(),
        'status' => 'paid',
    ]);

    // Create payment record for this order
    $payment = $order->payment()->create([
        'method' => $paymentMethod,
        'status' => 'paid',
        'transaction_id' => null,
    ]);

    foreach ($cartService->getUserCart() as $item) {
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'price' => $item->product->price,
        ]);
        $item->product->decrement('stock', $item->quantity);
    }

    $cartService->clearCart();
    session()->forget('checkout_address_id');
    session()->forget('checkout_payment_method');

    return redirect()->route('orders.show', $order->id)
        ->with('success', 'Thank you for your purchase!');
}

public function userOrders()
{
    $orders = Order::with('items.product', 'address')
        ->where('user_id', Auth::id())
        ->latest()
        ->get();

    return view('orders.user', compact('orders'));
}

}
