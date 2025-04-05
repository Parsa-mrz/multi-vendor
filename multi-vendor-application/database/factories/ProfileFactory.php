<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory class for generating MyProfile model instances
 *
 * This factory creates MyProfile instances with automatically associated
 * User records through the User factory relationship.
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state
     *
     * Generates default attributes for a MyProfile instance including
     * user relationship and basic personal information.
     *
     * @return array<string, mixed> Array of default attributes for MyProfile model
     */
    public function definition(): array
    {

        return [
            'user_id' => User::factory(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'phone_number' => fake()->phoneNumber(),
        ];
    }
}
