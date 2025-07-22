<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

class HomeController extends Controller
{


        protected $productRepository;
    /**
     *
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Summary of __construct
     * connecting wuth productrepository and adding guard
     * @param \App\Repositories\ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->middleware('auth')->except(['index']);
        $this->productRepository=$productRepository;
    }

    /**
     * Displaying the index page
     *@author=SSA
     * @return \Illuminate\Contracts\Support\Renderable
     */
public function index()
{
    //Ring Category
    $rings = Product::whereHas('category', fn($q) => $q->where('name', 'Rings'))->latest()->take(6)->get();

    //Necklace Category
    $necklaces = Product::whereHas('category', fn($q) => $q->where('name', 'Necklaces'))->latest()->take(6)->get();

    //Bracelet Category
    $bracelets = Product::whereHas('category', fn($q) => $q->where('name', 'Bracelets'))->latest()->take(6)->get();

    //Limited edition with high price range
    $limitedEditionProducts = Product::where('price', '>',500) ->orderBy('price', 'desc')->take(4)->get();


return view('home', compact('rings', 'necklaces', 'bracelets', 'limitedEditionProducts'));
}
}

