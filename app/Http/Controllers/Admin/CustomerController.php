<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class CustomerController extends Controller
{
    public function index()
    {
        // Fetch users who have placed orders, with order counts
        $customers = User::whereHas('orders')
            ->withCount('orders')
            ->paginate(10); // paginate for page links

        return view('admin.customers.index', compact('customers'));
    }
}
