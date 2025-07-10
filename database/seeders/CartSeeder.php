<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cart;
use App\Models\Product;
use GuzzleHttp\Handler\Proxy;
use App\Models\User;
class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        if (User::count() === 0) {
            User::factory()->create();
        }

        if (Product::count() === 0) {
            Product::factory(10)->create();
        }

        Cart::factory(20)->create();

    }
}
