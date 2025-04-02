<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory class for generating Vendor model instances
 *
 * This factory creates Vendor instances with automatically associated
 * User records (with 'vendor' role) and their associated Profiles.
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vendor>
 */
class VendorFactory extends Factory
{
    /**
     * Define the model's default state
     *
     * Generates default attributes for a Vendor instance including
     * an associated User with 'vendor' role and vendor-specific details.
     *
     * @return array<string, mixed> Array of default attributes for Vendor model
     */
    public function definition(): array
    {
        $user = User::factory()->create([
            'role' => 'vendor',
        ]);

        return [
            'user_id' => $user->id,
            'store_name' => fake()->company(),
            'description' => fake()->text(),
            'is_active' => fake()->boolean(),
        ];
    }
}
