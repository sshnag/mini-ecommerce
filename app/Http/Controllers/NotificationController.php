<?php

namespace App\Http\Controllers;

use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as Auths;


class NotificationController extends Controller
{
    public function markAsRead($id)
    {
$notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['status' => 'read']);
    }
}
