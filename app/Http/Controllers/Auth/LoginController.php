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

        // Store the current session ID before login for cart/wishlist transfer
        session(['guest_session_id' => session()->getId()]);

        if (!Auth::attempt($credentials, $request->filled('remember'))) {
            return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
        }

        $user = User::with('roles')->find(Auth::id());
        if ($user->hasAnyRole(['superadmin', 'admin'])) {
            Auth::logout();
            return back()->withErrors(['email' => 'Please use admin login']);
        }

        // FIRE THE LOGIN EVENT MANUALLY
        event(new \Illuminate\Auth\Events\Login('web', $user, false));

        $request->session()->regenerate();
        
        // Call the authenticated method to handle the redirect
        return $this->authenticated($request, $user);
    }

  public function adminLogout(Request $request){
    Auth::guard('admin')->logout();
    $request->session()->forget('admin_logged_in');
    return redirect('/admin/login');

  }
  public function userLogout(Request $request){
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
  }
    protected function authenticated(Request $request, $user)
    {
        // Store the current session ID before login for cart/wishlist transfer
        session(['guest_session_id' => session()->getId()]);

        return redirect()->intended($this->redirectPath());
    }
}
