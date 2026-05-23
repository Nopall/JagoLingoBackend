<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Buat beberapa data produk dengan Faker
        foreach (range(1, 10) as $index) {
            Product::create([
                'name' => $faker->word,
                'description' => $faker->paragraph,
                'price' => $faker->numberBetween(10000, 50000),
                'weight' => $faker->numberBetween(100, 1000),
                'uom' => 'kg',
                'commodity_id' => $faker->numberBetween(1, 3),
            ]);
        }
    }
}
