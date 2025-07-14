<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */public function index()
{
    // Summary numbers
    $totalProducts = Product::count();
    $ordersToday = Order::whereDate('created_at', today())->count();
    $newUsers = User::whereDate('created_at', today())->count();
    $totalRevenue = Order::sum('total_amount');

    // Chart Data (7 days)
    $days = collect(range(6, 0))->map(function ($i) {
        return now()->subDays($i)->format('D');
    });

    $orderCounts = collect(range(6, 0))->map(function ($i) {
        return Order::whereDate('created_at', now()->subDays($i))->count();
    });

    // Top 5 product categories
    $topCategories = Product::with('category')->selectRaw('category_id, COUNT(*) as total')->groupBy('category_id')->orderByDesc('total')->take(5)->get()->map(function ($item) {
        return [
            'name' => ucfirst(optional($item->category)->name ?? 'Unknown'),
            'count' => $item->total
        ];
    });

    return view('admin.dashboard', [
        'totalProducts' => $totalProducts,
        'ordersToday' => $ordersToday,
        'newUsers' => $newUsers,
        'totalRevenue' => $totalRevenue,

        'orderLabels' => $days,
        'orderCounts' => $orderCounts,

       'categoryLabels' => $topCategories->pluck('name'),
    'categoryCounts' => $topCategories->pluck('count'),
    ]);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
