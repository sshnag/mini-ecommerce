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

    public function __construct(ProductRepository $productRepository)
    {
        $this->middleware('auth')->except(['index']);
        $this->productRepository=$productRepository;
    }

    /**
     * Show the index home page showcasing the lastest jewels
     *@author=SSA
     * @return \Illuminate\Contracts\Support\Renderable
     */
public function index()
{
    $rings = Product::whereHas('category', fn($q) => $q->where('name', 'Rings'))
        ->latest()
        ->take(6)
        ->get();

    $necklaces = Product::whereHas('category', fn($q) => $q->where('name', 'Necklaces'))
        ->latest()
        ->take(6)
        ->get();

    $bracelets = Product::whereHas('category', fn($q) => $q->where('name', 'Bracelets'))
        ->latest()
        ->take(6)
        ->get();

 $limitedEditionProducts = Product::where('stock', '<=', 5) // Adjust 5 as needed
    ->orderBy('stock', 'asc')
    ->take(4)
    ->get();


return view('home', compact('rings', 'necklaces', 'bracelets', 'limitedEditionProducts'));
}
}

