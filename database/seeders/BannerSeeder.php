<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use Faker\Factory as Faker;

class BannerSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Buat beberapa data produk dengan Faker
        foreach (range(1, 10) as $index) {
            Notification::create([
                'title' => $faker->word,
                'description' => $faker->paragraph,
                'type' => 'all'
            ]);
        }
    }
}
