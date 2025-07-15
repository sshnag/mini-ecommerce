<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Repositories\ProductRepository;
use App\Services\ProductService;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    protected $productRepo;
    protected $productService;

    public function __construct(ProductRepository $productRepo, ProductService $productService)
    {
        $this->productRepo = $productRepo;
        $this->productService = $productService;

    }

   public function index()
{
      $products = Product::with('category')->paginate(5);
    return view('admin.products.index', compact('products'));
}

   public function create()
{
    $categories = Category::all(); // or paginate if many
    return view('admin.products.create', compact('categories'));
}


    public function store(StoreProductRequest $request)
    {
        try {
    $product = $this->productService->createProduct(
        $request->validated(),
        $request->file('image')
    );
   return redirect()
    ->route('admin.products.index')
    ->with('success', 'Product created successfully');
} catch (\Exception $e) {
    return back()->withInput()->withErrors(['error' => $e->getMessage()]);
}

    }

  public function show($custom_id)
{
    $product = Product::with('category')->where('custom_id', $custom_id)->firstOrFail();

    return view('products.show', compact('product'));
}



    public function edit(Product $product)
{
    $categories = Category::all();
    return view('admin.products.edit', compact('product', 'categories'));
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
    ->route('admin.products.index')
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
        // This single line does all authorization via your ProductPolicy
        $this->authorize('delete', $product);

        $this->productService->deleteProduct($product);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully');

    } catch (\Exception $e) {
        return back()
            ->withErrors(['error' => 'Deletion failed: ' . $e->getMessage()]);
    }
}

}
