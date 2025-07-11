<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Repositories\ProductRepository;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    protected $productRepo;
    protected $productService;

    public function __construct(ProductRepository $productRepo, ProductService $productService)
    {
        $this->productRepo = $productRepo;
        $this->productService = $productService;

        // Apply superadmin middleware only to destroy method
        $this->middleware('can:superadmin')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = $this->productRepo->allPaginated();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            $product = $this->productService->createProduct(
                $request->validated(),
                $request->file('image') // Pass the image file directly
            );

            return redirect()
                ->route('admin.products.show', $product->custom_id) // Use custom_id
                ->with('success', 'Product created successfully');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Product creation failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('category', 'reviews', 'supplier');
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            $this->productService->updateProduct(
                $product,
                $request->validated(),
                $request->file('image') // Pass the image file directly
            );

            return redirect()
                ->route('admin.products.show', $product->custom_id) // Use custom_id
                ->with('success', 'Product updated successfully');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Product update failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            // Verify superadmin permission
            if (!Gate::allows('superadmin')) {
                abort(403, 'Only superadmins can delete products');
            }

            // Delete via service
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
