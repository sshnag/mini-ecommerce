<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class SupplierController extends Controller
{
      public function __construct()
    {
        $this->middleware('role:admin|superadmin');
    }
  public function index()
{
    $supplierRole = Role::where('name', 'supplier')->where('guard_name', 'web')->first();

    if (!$supplierRole) {
        abort(404, 'Supplier role not found.');
    }

    // Get users with 'supplier' role (web guard)
    $suppliers = User::role('supplier', 'web')->paginate(10);

    return view('admin.suppliers.index', [
        'users' => $suppliers,
        'pageTitle' => 'Suppliers'
    ]);
}
    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign supplier role
        $user->assignRole('supplier');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Supplier created successfully',
                'user' => $user
            ]);
        }

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier created successfully');
    }
}
