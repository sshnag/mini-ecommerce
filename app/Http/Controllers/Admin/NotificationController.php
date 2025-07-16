<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class NotificationController extends Controller
{
    public function markAsRead(Request $request)
    {
        $user = auth()->user();

        if ($request->mark_all) {
            $user->unreadNotifications->markAsRead();
        } elseif ($request->notification_id) {
            $user->unreadNotifications()
                 ->where('id', $request->notification_id)
                 ->update(['read_at' => now()]);
        }

        return response()->json(['success' => true]);
    }
}
