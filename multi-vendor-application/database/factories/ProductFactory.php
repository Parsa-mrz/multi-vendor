<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);
        $price = $this->faker->randomFloat(2, 9.99, 999.99);
        $hasSale = $this->faker->boolean(30);


        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(3),
            'price' => $price,
            'sale_price' => $hasSale ? $price * $this->faker->randomFloat(2, 0.5, 0.95) : null,
            'quantity' => $this->faker->numberBetween(0, 100),
            'discount' => $hasSale ? $this->faker->numberBetween(5, 50) : 0,
            'image' => "https://picsum.photos/200/300?random=" . $this->faker->numberBetween(1, 100),
            'product_category_id' => ProductCategory::factory(),
            'vendor_id' => Vendor::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
