<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(){
        $user=User::find(Auth::id());
        $orders=$user->orders()->latest()->paginate(10);
        return view('profile.index',compact('user','orders'));
    }
   public function edit(){
    return view('profile.edit',['user'=>Auth::user()]);
   }

   public function update(Request $request){
    $request->validate([
        'name'=>'required|string|max:255',
        'profile_image'=>'nullable|image|mimes:png,jpg,jpeg,gif|max:2048',
    ]);
   $user=User::find(Auth::id());
   $user['name']=$request['name'];
   if ($request->hasFile('profile_image')) {
    $filename=time().'.'.$request->file('profile_image')->extension();
    $request['profile_image']->move(public_path('uploads/profile_images'),$filename);
    $user['profile_image']='uploads/profile_images/'.$filename;

   }
   $user->save();
return redirect()->route('profile.index')->with('success','Profile updated successfully');
   }
}
