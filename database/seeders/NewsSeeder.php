<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;
use Faker\Factory as Faker;

class NewsSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Seeder data untuk berita
        foreach (range(1, 5) as $index) {
            News::create([
                'title' => $faker->sentence,
                'content' => $faker->paragraph,
                'author' => $faker->name,
                'image_url' => $faker->imageUrl(),
                'type' => 'berita',
                'published_at' => $faker->dateTimeThisMonth()
            ]);
        }

        // Seeder data untuk artikel
        foreach (range(1, 5) as $index) {
            News::create([
                'title' => $faker->sentence,
                'content' => $faker->paragraph,
                'author' => $faker->name,
                'image_url' => $faker->imageUrl(),
                'type' => 'artikel',
                'published_at' => $faker->dateTimeThisMonth()
            ]);
        }
    }
}
