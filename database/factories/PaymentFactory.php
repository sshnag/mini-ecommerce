<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
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
            'order_id'=>Order::inRandomOrder()->first()?->id,
            'method'=>fake()->randomElement(['paypal','card','cash']),
            'status'=>fake()->randomElement(['paid','pending','failed']),
            'transaction_id'=>fake()->uuid()
        ];
    }
}
