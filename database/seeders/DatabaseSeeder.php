<?php

namespace Database\Seeders;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */public function run()
{
    $this->call([
        RoleSeeder::class,
    ]

    );
    // Seed users
    $users = User::all();
    foreach ($users as $user) {
        $user->custom_id = 'USER-' . str_pad($user->id, 6, '0', STR_PAD_LEFT);
        $user->save();
    }

    // Seed products
    $products = Product::all();
    foreach ($products as $product) {
        $product->custom_id = 'PROD-' . str_pad($product->id, 6, '0', STR_PAD_LEFT);
        $product->save();
    }
}
}
