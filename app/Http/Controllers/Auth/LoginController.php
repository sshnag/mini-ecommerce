<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use App\Models\User;
class LoginController extends Controller
{
    use HasRoles;
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login'); // Regular user login view
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
    $user = User::with('roles')->find(Auth::id());
 if ($user->hasAnyRole(['superadmin', 'admin'])) {
        Auth::logout();
        return back()->withErrors(['email' => 'Please use admin login']);
    }

        $request->session()->regenerate();
        return redirect()->intended(route('home'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
    protected function authenticated(Request $request, $user)
{if ($user->hasRole('supplier')) {
        return redirect()->route('supplier.dashboard');
    } else {
        return redirect()->route('home'); // normal user homepage
    }
}
}
