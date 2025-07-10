<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Product;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user=User::inRandomOrder()->first();
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();
        return [
            //
            'user_id'=>$user?->id,
            'product_id'=>$product?->id,
            'quantity'=>fake()->numberBetween(1,3)
        ];
    }
}
