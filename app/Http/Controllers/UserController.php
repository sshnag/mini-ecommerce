<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin|superadmin');
    }

    public function index(Request $request)
    {
        try {
            $query = User::with('roles')
                ->whereDoesntHave('roles', fn ($q) => $q->where('name', 'superadmin'))->latest();

            if ($request->filled('role')) {
                $query->role($request['role']);
            }

            $users = $query->paginate(10);
$allRoles = Role::where('name', '!=', 'superadmin')->get();
            return view(    'admin.users.index', compact('users', 'allRoles'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading users: '.$e->getMessage());
        }
    }

    public function create()
    {
        try {
            $roles = Role::where('name', '!=', 'superadmin')->get();
            return view('admin.users.create', compact('roles'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading create form: '.$e->getMessage());
        }
    }

 public function store(StoreUserRequest $request)
{
    try {
        $data = $request->validated();

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        if (!empty($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        // Redirect back to create form with success message and reset flag
        return redirect()
            ->route('superadmin.users.create')
            ->with('success', 'User created successfully')
            ->with('form_reset', true);

    } catch (\Exception $e) {
        return back()
            ->withInput()
            ->with('error', 'Error creating user: '.$e->getMessage());
    }
}
 public function updateRoles(Request $request, User $user)
{
    try {
        $validated = $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name'
        ]);

        // Get current roles before update
        $currentRoles = $user->getRoleNames()->toArray();

        // Sync new roles
        $user->syncRoles($validated['roles']);

        // Check if roles were actually changed
        $rolesChanged = array_diff($currentRoles, $validated['roles']) ||
                        array_diff($validated['roles'], $currentRoles);

        if ($rolesChanged) {
            // Invalidate remember token
            $user->setRememberToken(Str::random(60));

            // Delete all active sessions
            DB::table('sessions')
                ->where('user_id', $user['id'])  // Use $user->getKey() if id doesn't work
                ->delete();

            // If the modified user is currently logged in
            if (Auth::check() && Auth::user()['id'] === $user['id']) {
                auth()->guard('web')->logout();  // Explicitly specify the guard

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('info', 'Your roles have been updated. Please log in again.');
            }

            // Optionally send notification
            // $user->notify(new RolesChangedNotification($validated['roles']));
        }

        return back()->with('success', 'User roles updated successfully.');

    } catch (\Exception $e) {
        return back()->with('error', 'Error updating roles: '.$e->getMessage());
    }
}

    public function edit(User $user)
    {
        try {
            $roles = Role::where('name', '!=', 'superadmin')->get();
            return view('admin.users.edit', compact('user', 'roles'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading edit form: '.$e->getMessage());
        }
    }

    public function update(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'name'  => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,'.$user['id'],
            ]);

            $user->update($validated);

            return redirect()
                ->route('admin.users.index') // Changed from superadmin to admin
                ->with('success', 'User updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating user: '.$e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()
                ->route('admin.users.index') // Changed from superadmin to admin
                ->with('success', 'User deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting user: '.$e->getMessage());
        }
    }
}
