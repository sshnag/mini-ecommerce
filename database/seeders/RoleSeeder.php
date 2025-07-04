<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions first
        $permissions = [
            'manage products',
            'view orders',
            'manage categories',
            'assign roles',
            'view all users',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => 'web',
            ]);
        }

        //  Create roles
        $roles = ['superadmin', 'admin', 'supplier', 'user'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Assign permissions to roles
        Role::findByName('admin')->givePermissionTo([
            'manage products',
            'view orders',
            'manage categories',
        ]);

        Role::findByName('supplier')->givePermissionTo([
            'manage products',
            'view orders',
        ]);

        Role::findByName('superadmin')->givePermissionTo(Permission::all());

        // Create default Superadmin
        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('supersecret123'),
            ]
        );

        $superadmin->assignRole('superadmin');
    }
}
