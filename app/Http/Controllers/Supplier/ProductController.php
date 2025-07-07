<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;




class ProductController extends Controller
{
    //
    /**
     * Summary of index
     * displaying the product list for admin view
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $product=Product::where('user_id',Auth::id())->paginate(5);
        return view('admin.products.index');
    }
    /**
     * Summary of create
     * inserting new product from admin site
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('admin.products.create');

    }
    /**
     * Summary of store
     * storing data from create form
     * @param \App\Http\Requests\StoreProductRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreProductRequest $request){
        $data=$request->validated();
        $data['user_id']=Auth::id();
        $data['custom_id'] = Str::uuid();
        $data['image']=$request->file('image')?->store('products','public');
Product::create($data);
return redirect()->route('admin.products.index')->with('success','Product is added');
    }

    /**
     * Summary of edit
     * Editing the product lists
     * @param \App\Models\Product $product
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Product $product)
    {
            abort_if($product->user_id !==Auth::id(),403);
            return view('admin.products.');
    }

    /**
     * Summary of update
     * Updating the products' data
     * @param \App\Http\Requests\StoreProductRequest $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StoreProductRequest $request,Product $product){
        abort_if($product->user_id !== Auth::id(),403);
        $data= $request->validated();
        if ($request->hasFile('image')) {
            # code...
            $data['image']=$request->file('image')->store('products','public');

        }

        $product->update($data);
return redirect()->route('admin.products.index')->with('success','Product is updated');
    }

    /**
     * Summary of destroy
     * Deleting the selected product's(s) data
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product){
        abort_if($product->user_id !==Auth::id(),403);
        $product->delete();
        return back()->with('success','Product is archieved');
    }
}
