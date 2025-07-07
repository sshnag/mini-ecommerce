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
    public function index()
    {
        $product=Product::where('user_id',Auth::id())->paginate(5);
        return view('admin.products.index');
    }
    public function create()
    {
        return view('admin.products.create');

    }
    public function store(StoreProductRequest $request){
        $data=$request->validated();
        $data['user_id']=Auth::id();
        $data['custom_id'] = Str::uuid();
        $data['image']=$request->file('image')?->store('products','public');
Product::create($data);
return redirect()->route('admin.products.index')->with('success','Product is added');
    }
    public function edit(Product $product)
    {
            abort_if($product->user_id !==Auth::id(),403);
            return view('admin.products.');
    }
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
    public function destroy(Product $product){
        abort_if($product->user_id !==Auth::id(),403);
        $product->delete();
        return back()->with('success','Product is archieved');
    }
}
