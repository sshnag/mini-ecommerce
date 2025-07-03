<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */public function run()
{
    $users = User::orderBy('id')->get();
    foreach ($users as $user) {
        $user->custom_id = 'USER-' . str_pad($user->id, 6, '0', STR_PAD_LEFT);
        $user->save();
    }
}
}
