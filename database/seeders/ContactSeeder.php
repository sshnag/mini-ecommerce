<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Contact;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         $faker = Faker::create();
        foreach (range(1, 20) as $index) {
            Contact::create([
                'name'       => $faker->name,
                'email'      => $faker->safeEmail(),
                'subject'   =>$faker->sentence(5),
                'message'    => $faker->sentence(100),
                'status'     => $faker->randomElement(['new', 'read', 'replied']),

            ]);

        }
    }
}
