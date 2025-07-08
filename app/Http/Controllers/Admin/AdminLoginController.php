<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use PhpParser\Node\Stmt\Return_;

class AdminLoginController extends Controller
{
    //
     public function __construct(){
            $this->middleware('guest:admin')->except('logout');
     }
     public function showLoginForm(){
        return view('admin.login');
     }
     public function login(Request $request){
        $credentials=$request->validate([
            'email'=>'required|email',
            'password'=>'required|string|min:8',

        ]);
        if (!Auth::guard('admin')->attempt($credentials,$request->filled('remember'))) {
            # code...
            return back()->withErrors(['email'=>'Admin access Denied', 'passsword'=>'Wrong Password'])->withInput();
        }
        $user=Auth::with('roles')->guard('admin')->user();
        if (!$user->hasanyRoles('superadmin','admin')) {
            # code...
            Auth::guard('admin')->logout();
            return back()->withErrors(['email'=>'Wrong email']);
        }
        $request->session()->regenerate();
        return redirect()->intended(route('admin.dashboard'));
     }
     public function logput(Request $request){
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
          return redirect('/admin/login');
     }
}
