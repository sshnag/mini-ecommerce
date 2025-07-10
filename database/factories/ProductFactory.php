<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Category;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $user = User::role('supplier')->inRandomOrder()->first();

        return [
            //
            'id'=>(string)Str::uuid(),
            'user_id'=>$user?->id ?? User::factory()->create()->id,
            'category_id'=>Category::inRandomOrder()->first()->id,
            'name'=>fake()->words(2,true),
            'description'=>fake()->paragraph(),
            'price'=>fake()->randomFloat(2,100,100),
            'stock'=>fake()->numberBetween(5,50),
            'image'=>'images/products/sample'.rand(1,5) .'.jpg',

        ];
    }
}
