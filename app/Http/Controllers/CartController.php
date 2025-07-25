<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartRequest;
use App\Services\CartService;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    protected $cartService;

    /**
     * Summary of __construct
     * @param \App\Services\CartService $cartService
     * Inject CartService into the controller.
     */
    public function __construct(CartService $cartService)
    {
        $this->middleware('auth');
        $this->cartService = $cartService;
    }

    /**
          * Show full cart page.
     * @param \App\Services\CartService $cartService
     * @return \Illuminate\Contracts\View\View
     */
    public function index(CartService $cartService)
{
    $cartItems = $cartService->getUserCart();
    $total = $cartService->getTotal();
    $recommended = Product::inRandomOrder()->take(4)->get();

    return view('cart.index', compact('cartItems', 'total', 'recommended'));
}


 /**
     * Add item to cart
  * @param \Illuminate\Http\Request $request
  * @return \Illuminate\Http\RedirectResponse
  */
 public function store(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $cartItem = Cart::updateOrCreate(
        ['user_id' =>Auth::user()->id, 'product_id' => $request->product_id],
        ['quantity' => DB::raw("quantity + {$request->quantity}")]
    );

    if ($request->ajax()) {
        $count = Cart::where('user_id', User::find(Auth::id()))->count();

        return response()->json([
    'message' => 'Added to Bag!',
    'cartCount' => Cart::where('user_id', Auth::user()->id)->count()
]);

    }

    return redirect()->back()->with('success', 'Product added to cart.');
}

    /**
     *
     * Remove a cart item.
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->cartService->removeFromCart($id);
        return redirect()->back()->with('success', 'Item removed from cart.');
    }

    /**
     * Clear the entire cart.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clear()
    {
        $this->cartService->clearCart();
        return redirect()->back()->with('success', 'Cart cleared.');
    }
    /**
     * Updating the amount of items in cart
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request,$id){
        $request->validate([
            'quantity'=>'required|integer|min:1',
        ]);
        $cartItem=Cart::with('product')->findOrFail($id);
        if ($request['quantity'] > $cartItem->product->stock) {
            return back()->with('error', "No more items availbel more than {$cartItem->product->stock}. ");
        }
        $cartItem->update(['quantity'=>$request['quantity']]);
        return back()->with('success','Cart Updated Successfully!');
    }
}
