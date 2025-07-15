<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\Product;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'order_id' => Order::factory(), // fallback
            'product_id'=>Product::inRandomOrder()->first()?->id,
            'quantity'=>fake()->numberBetween(1,5),
            'price'=>fake()->randomFloat(1,50,3000)
        ];
    }
}
