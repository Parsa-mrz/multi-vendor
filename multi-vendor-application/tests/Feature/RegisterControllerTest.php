<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Class RegisterControllerTest
 *
 * Tests the functionality of the RegisterController, specifically the user registration process.
 * This class contains feature tests to verify successful registration and validation error handling
 * for the API endpoint /api/v1/register.
 *
 * @package Tests\Feature
 */
class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a new user can be registered successfully.
     *
     * This test sends a valid registration request to the /api/v1/register endpoint
     * and verifies that the response contains the expected structure and data,
     * as well as confirming the user is stored in the database.
     *
     * @return void
     */
    #[Test]
    public function it_registers_a_new_user_successfully()
    {
        $data = [
            'email' => 'john@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/v1/register', $data);

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => ['id', 'email'],
                ],
            ])
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
    }

    /**
     * Test that registration fails with an invalid email.
     *
     * This test sends a registration request with an invalid email to the /api/v1/register endpoint
     * and verifies that the response returns a 422 status with appropriate validation errors.
     *
     * @return void
     */
    #[Test]
    public function it_fails_to_register_with_invalid_email()
    {
        $data = [
            'email' => 'invalid-email',
            'password' => 'short',
        ];

        $response = $this->postJson('/api/v1/register', $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
