<?php

// app/Http/Controllers/ShopController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')
            ->where('stock', '>', 0)
            ->whereNull('deleted_at');

        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        $products = $query->latest()->paginate(12);

        return view('products.index', compact('products'));
    }
}
