<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
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

    return view('admin.orders.index', compact('orders'));
    }
    /**
     * Summary of show
     * displaying orderitems
     * @param \App\Models\Order $order
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Order $order)
    {
        abort_if($order->user_id !==Auth::id(),403);
        $order->load('orerItems.product');
        return view('orders.show',compact('order'));
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
}
