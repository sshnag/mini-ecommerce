<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartRequest;
use App\Services\CartService;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    public function index(CartService $cartService)
{
    $cartItems = $cartService->getUserCart();
    $total = $cartService->getTotal();
    $recommended = Product::inRandomOrder()->take(4)->get();

    return view('cart.index', compact('cartItems', 'total', 'recommended'));
}


    /**
     * Add item to cart (for AJAX or regular POST).
     */
   public function store(Request $request)
{
    $product = Product::findOrFail($request->product_id);

    Cart::updateOrCreate(
        ['user_id' => Auth::id(), 'product_id' => $product->id],
        ['quantity' => DB::raw('quantity + ' . $request->quantity), 'price' => $product->price]
    );
    return back()->with('success', "{$product->name} has been added to your Bag.");
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
