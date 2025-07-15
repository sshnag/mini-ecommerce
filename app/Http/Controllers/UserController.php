<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Spatie\Permission\Models\Role;
class UserController extends Controller
{
    /**
     * Summary of index
     * Displaying users ;ists from superadmin site
     * @return \Illuminate\Contracts\View\View
     */
public function index(Request $request)
{
    $query = User::query()->with('roles');

    // Filter by selected role (if any)
    if ($request->has('role') && $request->role !== '') {
        $query->role($request->role); // From Spatie:role()
    }

    // Exclude superadmin from results
    $query->whereDoesntHave('roles', function ($q) {
        $q->where('name', 'superadmin');
    });

    $users = $query->paginate(10);

    // Get all assignable roles
    $allRoles = \Spatie\Permission\Models\Role::where('name', '!=', 'superadmin')->get();

    return view('admin.users.index', compact('users', 'allRoles'));
}


/**
 * Summary of updateRoles
 * Updating User roles in superadmin site
 * @param \Illuminate\Http\Request $request
 * @param \App\Models\User $user
 * @return \Illuminate\Http\RedirectResponse
 */
public function updateRoles(Request $request, User $user)
{
    $validated = $request->validate([
        'roles' => 'required|array',
        'roles.*' => 'exists:roles,name'
    ]);

    $user->syncRoles($validated['roles']);

    return back()->with('success', 'User roles updated successfully');
}

    /**
     * Summary of create
     * Inserting new users from superdamin site only
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        //
        return view('admin.users.create');
    }

    /**
     * Summary of store
     * Storing Users' data from create form
     * @param \App\Http\Requests\StoreUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreUserRequest $request)
    {
        //
        $data=$request->validated();
        $user=User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>bcrypt($data['password']),
        ]);
        if (isset($data['role'])) {
            # code...
            $user->assignRole($data['role']);

        }
        return redirect()->route('admin.users.index')->with('success','New User Added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        //
        return view('admin.users.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
        $user->delete();
        return view('admin.users.index');
    }
}
