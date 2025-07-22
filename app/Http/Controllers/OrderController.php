<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Address;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Notifications\NewOrderNotification;

class OrderController extends Controller
{
    /**
     * Summary of index
     * ADMIN: List orders (filtered by status).
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $status = request()->input('status');

        $orders = Order::with(['user', 'orderItems.product'])
            ->when($status && $status !== 'all', fn($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Summary of updateStatus
     * ADMIN: Update order status.
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,shipped,cancelled',
        ]);

        $order->update($validated);

        return back()->with('success', 'Order status updated');
    }

    /**
     * Summary of show
     * ADMIN: Show order details page.
     * @param \App\Models\Order $order
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product', 'address']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Summary of destroy
     * ADMIN: Archive (delete) order.
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return back()->with('success', 'Order is archived');
    }

    /**
     * Summary of showShippingForm
     * USER: Show shipping form (checkout step 1).
     * @return \Illuminate\Contracts\View\View
     */
    public function showShippingForm()
    {
        return view('checkout.shipping', ['user' => Auth::user()]);
    }

    /**
     * Summary of storeShipping
     * USER: Store shipping address.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeShipping(Request $request)
    {
        $data = $request->validate([
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'payment_method' => 'required|string|in:paypal,card,cod',
        ]);

        $address = Address::create([
            'user_id'     => Auth::id(),
            'street'      => $data['street'],
            'city'        => $data['city'],
            'postal_code' => $data['postal_code'],
            'country'     => $data['country'],
        ]);

        session([
            'checkout_address_id' => $address['id'],
            'checkout_payment_method' => $data['payment_method'],
        ]);

        return redirect()->route('checkout.review');
    }

    /**
     * Summary of showReview
     * USER: Show order review (checkout step 2).
     * @param \App\Services\CartService $cartService
     * @return \Illuminate\Contracts\View\View
     */
    public function showReview(CartService $cartService)
    {
        return view('checkout.review', [
            'cartItems' => $cartService->getUserCart(),
            'total' => $cartService->getTotal(),
            'address' => Address::where('user_id', Auth::id())
                        ->find(session('checkout_address_id')),
        ]);
    }

    /**
     * Summary of placeOrder
     * USER: Place order.
     * @param \App\Services\CartService $cartService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function placeOrder(CartService $cartService)
    {
        $user = Auth::user();
        $paymentMethod = session('checkout_payment_method');

        $order = Order::create([
            'user_id' => $user['id'],
            'address_id' => session('checkout_address_id'),
            'total_amount' => $cartService->getTotal(),
            'status' => 'paid',
        ]);

        $order->payment()->create([
            'method' => $paymentMethod,
            'status' => 'paid',
            'transaction_id' => null,
        ]);

        foreach ($cartService->getUserCart() as $item) {
            OrderItem::create([
                'order_id' => $order['id'],
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
            $item->product->decrement('stock', $item->quantity);
        }

        $cartService->clearCart();
        session()->forget(['checkout_address_id', 'checkout_payment_method']);

        // Notify Admins
        User::role(['admin', 'superadmin'])->each(function ($admin) use ($order) {
            $admin->notify(new NewOrderNotification($order));
        });

        return redirect()->route('orders.userShow', $order['id'])
            ->with('success', 'Thank you for your purchase!');
    }

    /**
     * Summary of userShow
     * USER: Show one of their own orders.
     * @param mixed $orderId
     * @return \Illuminate\Contracts\View\View
     */
    public function userShow($orderId)
    {
        $order = Order::with(['orderItems.product', 'address'])
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }

    /**
     * Summary of userOrders
    * USER: Order history list.
     * @return \Illuminate\Contracts\View\View
     */
    public function userOrders()
    {
        $orders = Order::with('orderItems.product', 'address')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('orders.user', compact('orders'));
    }

   /**
    * Summary of orderConfirmation
     * USER: Thank you / order confirmation page.
    * @param mixed $orderId
    * @return \Illuminate\Contracts\View\View
    */
   public function orderConfirmation($orderId)
{
    // Add ownership check
    $order = Order::with(['orderItems.product', 'address'])
        ->where('id', $orderId)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    return view('orders.confirmation', compact('order'));
}
}
