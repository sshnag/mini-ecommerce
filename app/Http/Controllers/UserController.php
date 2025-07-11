<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
class UserController extends Controller
{
    /**
     * Summary of index
     * Displaying users ;ists from superadmin site
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
{
    $users = User::whereDoesntHave('roles', function($query) {
            $query->where('name', 'superadmin');
        })
        ->with('roles')
        ->paginate(10);

    return view('admin.users.index', compact('users'));
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
        return redirect()->route('admin.users.index')->with('succeess','New User Added!');
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
        return view('superadmin.users.index');
    }
}
