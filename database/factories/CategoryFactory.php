<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name=fake()->word();
        return [
            //
                'name' => fake()->unique()->word(),
                'size_type'=>fake()->randomElement(['ring','bracelet','none']),
                'slug'=>Str::slug($name),
                'created_at'=>now(),
                'updated_at'=>now()
        ];
    }
}
