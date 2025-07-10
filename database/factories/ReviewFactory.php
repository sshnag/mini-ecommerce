<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
        protected $model = Review::class;

    public function definition(): array
    {

        return [
            //
            'user_id'=>User::inRandomOrder()->first()?->id,
            'product_id'=>Product::inRandomOrder()->first()?->id,
            'rating'=>fake()->numberBetween(1,5),
            'comment'=>fake()->sentence(8),

        ];
    }
}
