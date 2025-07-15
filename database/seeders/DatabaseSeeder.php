<?php

namespace Database\Seeders;

use App\Models\Category;
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

                // UserSeeder::class,
                // CategorySeeder::class,
                // AddressSeeder::class,
                // ProductSeeder::class,
                // CartSeeder::class,
                // ReviewSeeder::class,
                OrderSeeder::class,
                OrderItemSeeder::class,
                // PaymentSeeder::class,
                // AdminSeeder::class,
                // RoleSeeder::class,
    ]
    );

}
}
