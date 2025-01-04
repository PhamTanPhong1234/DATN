<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Faker\Factory as Faker;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 50; $i++) { 
            Product::create([
                'name' => $faker->word,
                'description' => $faker->text,
                'price' => $faker->randomFloat(2, 10, 1000), 
                'stock' => $faker->numberBetween(1, 100), 
                'category_id' => $faker->numberBetween(1, 10), 
            ]);
        }
    }
}
