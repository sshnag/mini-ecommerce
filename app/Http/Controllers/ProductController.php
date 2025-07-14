<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Repositories\ProductRepository;
use App\Services\ProductService;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    protected $productRepo;
    protected $productService;

    public function __construct(ProductRepository $productRepo, ProductService $productService)
    {
        $this->productRepo = $productRepo;
        $this->productService = $productService;

        $this->middleware('can:superadmin')->only('destroy');
    }

   public function index()
{
      $products = Product::with('category')->paginate(5);
    return view('admin.products.index', compact('products'));
}

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $product = $this->productService->createProduct(
                $request->validated(),
                $request->file('image')
            );

            return redirect()
                ->route('admin.products.show', $product->custom_id)
                ->with('success', 'Product created successfully');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Product creation failed: ' . $e->getMessage()]);
        }
    }

    public function show($custom_id)
    { $product = Product::with(['category', 'reviews', 'supplier'])
                ->where('custom_id', $custom_id)
                ->firstOrFail();

    return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            $this->productService->updateProduct(
                $product,
                $request->validated(),
                $request->file('image')
            );

            return redirect()
                ->route('admin.products.show', $product->custom_id)
                ->with('success', 'Product updated successfully');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Product update failed: ' . $e->getMessage()]);
        }
    }

    public function destroy(Product $product)
    {
        try {
            if (!Gate::allows('superadmin')) {
                abort(403, 'Only superadmins can delete products');
            }

            $this->productService->deleteProduct($product);

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product deleted successfully');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Product deletion failed: ' . $e->getMessage()]);
        }
    }
}
