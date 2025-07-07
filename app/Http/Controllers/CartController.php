<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartRequest;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
class CartController extends Controller
{
    /**
     * Summary of index
     * Displaying the cart
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        //
        $cartItem=Cart::with('product')->where('user_id',Auth::id())->get();
        return view('cart.index',compact('cartItem'));
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
     * Storing the cart's data
     * @param \App\Http\Requests\StoreCartRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreCartRequest $request)
    {
        //
        $validated=$request->validated();
        $cart=Cart::firstOrCreate(
            [
                'user_id'=>Auth::id(),
                'product_id'=>$validated['product_id']
            ],
            ['quantity'=>0]
        );
        $cart->increment('quantity', $validated['quantity']);
        return response()->json((['message'=>'Item added to the bag.']));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }


    /**
     * Summary of destroy
     * Deleteing the cart's data
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        //
        $cart=Cart::findOrFail($id);
        abort_if($cart->user_id !== Auth::id(),403);
        $cart->delete();
        return back()->with('success','Removed from the bag');
    }
}
