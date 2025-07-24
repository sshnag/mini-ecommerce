<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        // Use eager loading with specific relationship columns
        $user = User::find(Auth::id())->load([
            'orders' => function ($query) {
                $query->select('id', 'user_id', 'status', 'total_amount', 'created_at')
                      ->latest();
            }
        ]);

        // Paginate orders separately for better performance
        $orders = $user->orders()->paginate(10);

        return view('profile.index', compact('user', 'orders'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(UpdateProfileRequest $request)
    {
          set_time_limit(120);
        $user = User::find(Auth::id());
        $user->name = $request->name;

        if ($request->hasFile('profile_image')) {
            $this->processProfileImage($user, $request->file('profile_image'));
        }

        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully');
    }

    protected function processProfileImage($user, $file)
    {
        // Delete old image if exists
        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }

        // Store with optimized path
        $path = $file->store('profile_images', 'public');
        $user->profile_image = $path;
    }
}
