<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->create([
            'name' => 'Superadmin',
            'email' => 'admin@mailinator.com',
            'password' => bcrypt('admin123')
        ]);
    }
}
