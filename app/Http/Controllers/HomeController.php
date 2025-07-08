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
        return view('home',['latestjewel'=>$this->productRepository->getLatestJewels(8)]);
        }
}
