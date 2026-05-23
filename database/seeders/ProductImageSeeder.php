<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductImage;
use Faker\Factory as Faker;

class ProductImageSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Buat beberapa data gambar produk dengan Faker
        foreach (range(1, 20) as $index) {
            ProductImage::create([
                'product_id' => $faker->numberBetween(1, 10),
                'image' => $faker->imageUrl(640, 480, 'products', true),
            ]);
        }
    }
}
