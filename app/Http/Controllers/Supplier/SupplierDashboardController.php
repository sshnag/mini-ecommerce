<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Order;

class SupplierDashboardController extends Controller
{
    public function index()
    {
        $supplier = Auth::user();

        // Count supplier's products
        $productCount = Product::where('user_id', $supplier->id)->count();

        // Count orders that include products from this supplier
        $orderCount = Order::whereHas('orderItems.product', function ($query) use ($supplier) {
            $query->where('user_id', $supplier->id);
        })->count();

        return view('supplier.dashboard', compact('productCount', 'orderCount'));
    }
}
