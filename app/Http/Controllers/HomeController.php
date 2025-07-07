<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    /**
     * Show the index home page showcasing the lastest jewels
     *@author=SSA
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request,Product $product)
    {
        $latestjewel=Product::latest('created_at');

        return view('home',compact('product'));
    }
}
