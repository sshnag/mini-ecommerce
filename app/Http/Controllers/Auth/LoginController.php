<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.user-login'); // Regular user login view
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if (!Auth::attempt($credentials, $request->filled('remember'))) {
            return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
        }

        // Block admin access through this route
        if (Auth::user()->hasRole(['superadmin', 'admin'])) {
            Auth::logout();
            return back()->withErrors(['email' => 'Admin access denied here']);
        }

        $request->session()->regenerate();
        return redirect()->intended(route('user.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
