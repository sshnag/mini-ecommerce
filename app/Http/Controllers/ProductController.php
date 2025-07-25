<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Auth;

use App\Services\ProductService;

class ProductController extends Controller
{
    protected $productRepo;
    protected $productService;

    /**
     * Summary of __construct
     * Connect with repositories
     * @param \App\Repositories\ProductRepository $productRepo
     * @param \App\Services\ProductService $productService
     */
    public function __construct(ProductRepository $productRepo, ProductService $productService)
    {
        $this->productRepo    = $productRepo;
        $this->productService = $productService;

    }

    /**
     * Summary of index
     * ADMIN: Products list page
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $products = Product::with('category')->paginate(5);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Summary of create
     * ADMIN:Product create page
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $categories = Category::all(); // or paginate if many
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Summary of store
     * Store products' data
     * @param \App\Http\Requests\StoreProductRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreProductRequest $request)
    {
        try {
            $product = $this->productService->createProduct(
                $request->validated(),
                $request->file('image')
            );
            return redirect()->route('admin.products.index')->with('success', 'Product created successfully');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }

    }

    /**
     * Summary of show
     * Product details pages for admin/superadmin site and user site
     * @param mixed $custom_id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($custom_id)
    {
        $product = Product::with('category', 'reviews')->where('custom_id', $custom_id)->firstOrFail();
        // Only show admin view if the request is for an admin route
        if (request()->is('admin/products/*')) {
            // Extra check: only allow if admin guard is authenticated
            if (auth('admin')->check() && (auth('admin')->user()->hasRole('admin') || auth('admin')->user()->hasRole('superadmin'))) {
                return view('admin.products.show', compact('product'));
            } else {
                abort(403, 'Unauthorized');
            }
        }
        // Determine if product is in wishlist for current user or guest
        $inWishlist = false;
        if (\Auth::check()) {
            $inWishlist = \App\Models\Wishlist::where('user_id', \Auth::id())
                ->where('product_id', $product->id)
                ->exists();
        } else {
            $inWishlist = \App\Models\Wishlist::where('session_id', session()->getId())
                ->where('product_id', $product->id)
                ->exists();
        }
        return view('products.show', compact('product', 'inWishlist'));
    }

    /**
     * Summary of edit
     * Editing products' data
     * @param \App\Models\Product $product
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Updating the products' data
     *
     */

    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            $this->productService->updateProduct(
                $product,
                $request->validated(),
                $request->file('image')
            );

            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Product update failed: ' . $e->getMessage()]);
        }
    }

    /**
     * delete(archive) the selected products'data (only superadmin has permission for this)
     */

    public function destroy(Product $product)
    {
        try {
            // This single line does all authorization via your ProductPolicy
            $this->authorize('delete', $product);

            $this->productService->deleteProduct($product);

            return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Deletion failed: ' . $e->getMessage()]);
        }
    }

}
