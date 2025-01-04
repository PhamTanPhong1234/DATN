<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker; // Import Faker
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('CDSyncs_categories')->truncate();
    
        $faker = Faker::create();
    
        for ($i = 0; $i < 10; $i++) {
            Category::create([
                'name' => $faker->unique()->word, 
                'description' => $faker->text,
                
            ]);
        }
    }
}
