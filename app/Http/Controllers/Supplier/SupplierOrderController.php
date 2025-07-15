<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class SupplierOrderController extends Controller
{
    public function index()
    {
        $supplierId = Auth::id();

        // Assuming orders have a relation 'items' where each item has supplier_id
        $orders = Order::whereHas('items', function($q) use ($supplierId) {
            $q->where('supplier_id', $supplierId);
        })->paginate(15);

        return view('supplier.orders.index', compact('orders'));
    }
}
