<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $supplierRole=Role::firstOrCreate(['name'=>    'supplier']);
        //
        User::factory(3)->create()->each(function($user) use($supplierRole){
            $user->assignRole($supplierRole);
        });
            User::factory(10)->create();
    }

}
