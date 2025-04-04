<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vendor;
use App\Services\VendorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class VendorRegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    #[Test]
    public function test_successful_vendor_registration()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = [
            'store_name' => fake()->company(),
            'description' => fake()->text(),
        ];

        $vendorServiceMock = Mockery::mock(VendorService::class);
        $vendorServiceMock->shouldReceive('registerAsVendor')
            ->once()
            ->with($user, $data)
            ->andReturn($user);

        $this->app->instance(VendorService::class, $vendorServiceMock);

        $response = $this->postJson('/api/v1/vendor/register', $data);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Vendor registered successfully.',
            ]);
    }

    #[Test]
    public function test_store_name_already_taken()
    {
        $vendor = Vendor::factory()->create();
        $this->actingAs($vendor->user);

        $data = [
            'store_name' => $vendor->store_name,
            'description' => fake()->text(),
        ];

        $response = $this->postJson('/api/v1/vendor/register', $data);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The store name has already been taken.',
            ]);
    }

    #[Test]
    public function test_vendor_already_registered()
    {
        $vendor = Vendor::factory()->create();
        $this->actingAs($vendor->user);

        $data = [
            'store_name' => fake()->company(),
            'description' => fake()->text(),
        ];

        $response = $this->postJson('/api/v1/vendor/register', $data);

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'You are already registered as a vendor.',
            ]);
    }

    #[Test]
    public function test_validation_fails_without_required_fields()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson('/api/v1/vendor/register', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['store_name', 'description']);
    }
}
