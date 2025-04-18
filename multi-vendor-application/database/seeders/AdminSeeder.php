<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

use function bcrypt;
use function fake;
use function now;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    //
    public function run(): void
    {
        $user = User::firstOrCreate(
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

        if (! $user->profile) {
            $user->profile()->create();
        }
    }
}
