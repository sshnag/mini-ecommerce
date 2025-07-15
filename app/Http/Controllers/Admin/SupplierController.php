<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
{
    $supplierRole = Role::where('name', 'supplier')->where('guard_name', 'web')->first();

    if (!$supplierRole) {
        abort(404, 'Supplier role not found for web guard.');
    }

    $suppliers = User::role('supplier', 'web')->paginate(15);
    $allRoles = Role::where('name', '!=', 'superadmin')->get(); // <-- Add this line

    return view('admin.suppliers.index', [
        'users' => $suppliers,
        'allRoles' => $allRoles, // <-- Pass it to the view
        'pageTitle' => 'Suppliers'
    ]);
}

}
