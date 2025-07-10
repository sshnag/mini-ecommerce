<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Address;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
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
            'user_id'=>User::inRandomOrder()->first()?->id,
            'address_id'=>Address::inRandomOrder()->first()?->id,
            'total_amount'=>fake()->randomFloat(1,100,1000),
            'status'=>fake()->randomElement(['pending','paid','shipped','cancelled'])
        ];
    }
}
