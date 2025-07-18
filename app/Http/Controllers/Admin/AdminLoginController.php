<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    /**
     * Apply guest middleware to prevent logged-in admins from seeing the login page.
     * Allows logout even if already authenticated.
     * @author - SSA
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * Show the login form for admins.
     * Only accessible to guests (unauthenticated users).
     * @author - SSA
     * @return \Illuminate\Contracts\View\View
     */
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    /**
     * Handle admin login request with validation and role verification.
     * Rejects users who are not in the admin or superadmin roles.
     * @author - SSA
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validate login credentials
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        // Attempt authentication using admin guard
        if (!Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Invalid email or password.',
            ])->withInput();
        }

        $user = Auth::guard('admin')->user();

        // Manually check if user has admin or superadmin role
        $roleNames = $user->roles->pluck('name')->toArray();
        if (!in_array('admin', $roleNames) && !in_array('superadmin', $roleNames)) {
            Auth::guard('admin')->logout();
            return back()->withErrors([
                'email' => 'Access denied. You are not an admin.',
            ]);
        }

        // Regenerate session to prevent session fixation attacks
        $request->session()->regenerate();
        return redirect()->route('admin.dashboard');
    }

    /**
     * Log out the currently authenticated admin.
     * Invalidates and regenerates the session token.
     * @author - SSA
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
