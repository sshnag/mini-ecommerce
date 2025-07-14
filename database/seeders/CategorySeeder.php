<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Rings',
                'slug' => 'rings',
                'size_type' => 'ring'
            ],
            [
                'name' => 'Bracelets',
                'slug' => 'bracelets',
                'size_type' => 'bracelet'
            ],
            [
                'name' => 'Necklaces',
                'slug' => 'necklaces',
                'size_type' => 'none'
            ]
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']], // Check if slug exists
                $category // Create with these attributes if not
            );
        }
    }
}
