<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $product=Product::with('category')->paginate(5);
return view('admin.products.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        //
        $data=$request->validated();
        $data['custom_id']=Str::uuid();
        $data['image']=$request->file('image')->store('products','public');
        Product::create($data);
        return redirect()->route('admin.products.index')->with('success','Product is added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
        return view('admin.products.edit',compact('product'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreProductRequest $request, Product $product)
    {
        //
        $data=$request->validated();
        if ($request->hasFile('image')) {
            # code...
            $data['image']=$request->file('image')->store('products','public');}

            $product->update($data);
            return redirect()->route('admin.products.index')->with('Product is Updated Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
        $product->delete();
return back()->with('success','Product is archieved!');
    }
}
