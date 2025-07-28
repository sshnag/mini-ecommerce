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
        // Removed auth middleware so guests can add to cart
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

    if (Auth::check()) {
        $userId = Auth::id();
        $sessionId = null;
    } else {
        $userId = null;
        $sessionId = session()->getId();
    }

    $cartItem = Cart::updateOrCreate(
        ['user_id' => $userId, 'session_id' => $sessionId, 'product_id' => $request->product_id],
        ['quantity' => DB::raw("quantity + {$request->quantity}")]
    );

    // Get updated cart count
    $count = Cart::where(function($query) use ($userId, $sessionId) {
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }
    })->count();

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Added to Bag!',
            'cartCount' => $count
        ]);
    }

    return redirect()->back()->with([
        'success' => 'Product added to cart.',
        'cartCount' => $count
    ]);
}

public function destroy($id)
{
    $cartItem = Cart::findOrFail($id);

    // Get user/session info before deletion
    $userId = $cartItem->user_id;
    $sessionId = $cartItem->session_id;

    $cartItem->delete();

    // Get updated cart count
    $count = Cart::where(function($query) use ($userId, $sessionId) {
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }
    })->count();

    if (request()->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart.',
            'cartCount' => $count
        ]);
    }

    return redirect()->back()->with([
        'success' => 'Item removed from cart.',
        'cartCount' => $count
    ]);
}

public function clear()
{
    if (Auth::check()) {
        $userId = Auth::id();
        $sessionId = null;
    } else {
        $userId = null;
        $sessionId = session()->getId();
    }

    Cart::where(function($query) use ($userId, $sessionId) {
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }
    })->delete();

    if (request()->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Cart cleared.',
            'cartCount' => 0
        ]);
    }

    return redirect()->back()->with([
        'success' => 'Cart cleared.',
        'cartCount' => 0
    ]);
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
