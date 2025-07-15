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
        // Check supplier role exists
    $supplierRole = Role::where('name', 'supplier')->where('guard_name', 'web')->first();
if (!$supplierRole) {
    abort(404, 'Supplier role not found for web guard.');
}

$suppliers = User::role('supplier', 'web')->paginate(15);
return view('admin.users.index', ['users' => $suppliers, 'pageTitle' => 'Suppliers']);

}
}
