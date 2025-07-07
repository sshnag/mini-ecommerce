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
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $orders=Order::with('orderItems.products')->where('user_id',Auth::id())->latest()->get();
        return view('orders.index',compact('orders'));
    }
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
     * Store a newly created resource in storage.
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
    }
}
