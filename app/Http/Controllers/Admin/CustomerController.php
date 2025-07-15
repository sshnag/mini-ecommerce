<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        // Fetch users who have placed orders, with order counts
        $customers = User::whereHas('orders')
            ->withCount('orders')
            ->paginate(5);  // paginate for page links

        return view('admin.customers.index', compact('customers'));
    }
}
