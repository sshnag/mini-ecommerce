<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use JeroenNoten\LaravelAdminLte\View\Components\Form\Input;

class CategoryController extends Controller
{
    /**
     * Displaying categories lists (only admin and superadmin view)
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
    $categories = Category::withCount('products')->paginate(15);
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
public function show(Category $category, Request $request)
{
    $query = $category->products()->with('reviews');

    // Search filter
    if ($request->filled('search')) {
        $searchTerm = $request['search'];
        $query->where(function($q) use ($searchTerm) {
            $q->where('name', 'like', "%{$searchTerm}%")
              ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%{$searchTerm}%"));
        });
    }

    // Price Range Filter
    if ($request->filled('min_price')) {
        $query->where('price', '>=', $request['min_price']);
    }
    if ($request->filled('max_price')) {
        $query->where('price', '<=', $request['max_price']);
    }

    //sorting:highesst to lowest
    $sort=$request->input('sort','price_desc');
    switch ($sort) {
        case 'price_asc':
            $query->orderBy('price','asc');
            break;
             case 'name_asc':
            $query->orderBy('name','asc');
            break;
             case 'name_desc':
            $query->orderBy('name','desc');
            break;
             case 'newest':
            $query->orderBy('created_at','desc');
            break;
        default:
                $query->orderBy('price','desc');
    }

    $products = $query->paginate(9)->withQueryString();

    return view('categories.show', compact('category', 'products','sort'));
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
    $this->authorize('delete', $category);

    $category->delete();

    return redirect()->route('admin.categories.index')
        ->with('success', 'Category deleted successfully.');
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
