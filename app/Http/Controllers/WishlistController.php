<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    //
    public function index()   {
        $wishlist= Wishlist::where('user_id',User::find(Auth::id()))->get();
        return view('wishlist',compact('wishlist'));

    }
}
