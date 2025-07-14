<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Make sure 'admin' role exists
        Role::firstOrCreate(['name' => 'admin']);

        // Create admin user manually
        $admin = new User();
        $admin->name = 'Admin';
        $admin->email = 'admin@example.com';
        $admin->password = Hash::password('adminsecret123');
        $admin->save();

        // Set custom_id manually
        $admin->custom_id = 'USER-' . str_pad($admin->id, 6, '0', STR_PAD_LEFT);
        $admin->save();

        // Assign admin role
        $admin->assignRole('admin');
    }
}
