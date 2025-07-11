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
        //

//brain ကုန်ပ
    return view('admin.users.index');

    }

    public function datatable()
    {
        $users = User::whereDoesntHave('roles', function($query) {
                $query->where('name', 'superadmin');
            })
            ->with('roles')
            ->select(['id', 'name', 'email', 'created_at']);

        return DataTables::of($users)
            ->addColumn('role', function($user) {
                return $user->roles->first()->name ?? 'N/A';
            })
            ->addColumn('action', function($user) {
                return [
                    'edit' => route('admin.users.edit', $user->id),
                    'id' => $user->id
                ];
            })
            ->editColumn('created_at', function($user) {
                return $user->created_at->format('Y-m-d H:i');
            })
            ->make(true);
    }
    /**
     * Summary of create
     * Inserting new users from superdamin site only
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        //
        return view('superadmin.users.create');
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
        return redirect()->route('superadmin.users.index')->with('succeess','New User Added!');
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
