<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
class CustomerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $customers=User::whereHas('orders')->withCount('orders')->latest();
            return Datatables::of($customers)
            ->addColumn('name',fn($row)=>e($row->name))
            ->addColumn('email',fn($row)=>e($row->email))
            ->addColumn('orders_count',fn($row)=>e($row->orders_count))
            ->make(true);

        }
        return view ('admin.customers.index');
    }
}
