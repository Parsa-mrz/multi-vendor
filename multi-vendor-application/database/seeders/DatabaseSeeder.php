<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['role' => 'admin'],
            [
                'email' => 'admin@gmail.com',
                'password' => bcrypt('1234'),
                'email_verified_at' => now(),
                'is_active' => true,
                'last_login' => fake()->dateTime(),
                'role' => 'admin',
            ]
        );
    }
}
