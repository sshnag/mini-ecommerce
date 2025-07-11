<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string', 'min:8'],
    ]);

    if (!Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->withInput();
    }

    $user = Auth::guard('admin')->user();

    // Check for allowed roles (assuming you have a 'role' column)
    if (!in_array($user->role, ['admin', 'superadmin'])) {
        Auth::guard('admin')->logout();
        return back()->withErrors([
            'email' => 'Access denied. Not an admin.',
        ]);
    }

    $request->session()->regenerate();
    return redirect()->intended(route('admin.dashboard'));
}
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }
}
