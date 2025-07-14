<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Displaying categories lists (only admin and superadmin view)
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $categories = Category::paginate(5);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Summary of create
     * Inserting new categories
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Summary of store
     * storing categories' data
     * @param \App\Http\Requests\StoreCategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCategoryRequest $request)
    {
        Category::create($request->validated());
        return redirect()->route('admin.categories.index')->with('success', 'Category is added');
    }

    /**
     * Display products in a specific category
     * @param string $slug
     * @return \Illuminate\Contracts\View\View
     */
   public function show($slug)
{
    $category = Category::where('slug', $slug)->firstOrFail();

    $products = Product::where('category_id', $category->id)
        ->with(['category', 'reviews'])
        ->paginate(12);

    return view('categories.show', [
        'category' => $category,
        'products' => $products,
        'sizePresets' => $this->getSizePresets($category->size_type)
    ]);
}

    /**
     * Summary of edit
     * Editing the categories' datas
     * @param \App\Models\Category $category
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Summary of update
     * updating the categories' data
     * @param \App\Http\Requests\UpdateCategoryRequest $request
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return back()->with('success', 'Category is updated');
    }

    /**
     * Summary of destroy
     * deleting the selected category's data
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category is archived');
    }

    /**
     * Get size presets based on category type
     * @param string $sizeType
     * @return array
     */
    protected function getSizePresets($sizeType)
    {
        return match($sizeType) {
            'ring' => ['4', '4.5', '5', '5.5', '6', '6.5', '7', '7.5', '8'],
            'bracelet' => ['Small', 'Medium', 'Large', 'XL'],
            default => []
        };
    }
}
