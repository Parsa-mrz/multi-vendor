<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * Factory class for generating User model instances with associated profiles
 *
 * This factory creates User instances and automatically generates associated
 * MyProfile records through the configure method.
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory
     *
     * @static
     */
    protected static ?string $password;

    /**
     * Define the model's default state
     *
     * Generates default attributes for a User instance including email,
     * password, status, login time, and role.
     *
     * @return array<string, mixed> Array of default attributes for User model
     */
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'is_active' => fake()->boolean(),
            'last_login' => fake()->dateTime(),
            'role' => fake()->randomElement(['customer', 'admin', 'vendor']),
        ];
    }

    /**
     * Configure the factory with additional actions
     *
     * Sets up an after-creating hook to automatically generate a MyProfile
     * for each User created by this factory.
     *
     * @return self Returns the factory instance for method chaining
     */
    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            Profile::factory()->create([
                'user_id' => $user->id,
            ]);
        });
    }
}
