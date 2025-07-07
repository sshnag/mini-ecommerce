<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Displaying categories lists (only admin and superadmin view)
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        //
        $categories=Category::paginate(5);
        return view('admin.categories.index',compact('categories'));
    }

    /**
     * Summary of create
     * Inserting new categories
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        //
        return view('admin.categories.create');
    }

    /**
     * Summary of store
     * stroing categories' data
     * @param \App\Http\Requests\StoreCategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCategoryRequest $request)
    {
        //
        Category::create($request->validated());
        return redirect()->route('admin.categories.index')->with('success','Category is added');

    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Summary of edit
     * Editing the categories' datas
     * @param \App\Models\Category $category
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Category $category)
    {
        //
        return view('admin.categories.edit',compact('category'));
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
        //
        $category->delete();
        return back()->with('Success','category is Updated');
    }

    /**
     * Summary of destroy
     * deleting the seleted category's data
     * @param \App\Models\Category $category
     * @return \Illuminate\Contracts\View\View
     */
    public function destroy(Category $category)
    {
        //
        $category->delete();
        return view('admin.categories.index')->with('success','Category is archieved');
    }
}
