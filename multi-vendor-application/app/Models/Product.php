<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Class Product
 *
 * Represents a product available for sale in the system. It has attributes like name, description, price,
 * and quantity, as well as relationships with the vendor and category.
 *
 *
 * @property int $id The unique identifier for the product.
 * @property string $name The name of the product.
 * @property string $slug A URL-friendly version of the product name.
 * @property string $description A detailed description of the product.
 * @property float $price The regular price of the product.
 * @property float|null $sale_price The sale price of the product, if applicable.
 * @property int $quantity The quantity of the product available in stock.
 * @property int $product_category_id The ID of the product's category.
 * @property int $vendor_id The ID of the vendor selling the product.
 * @property string|null $image The image URL for the product.
 * @property \Illuminate\Support\Carbon $created_at Timestamp when the product was created.
 * @property \Illuminate\Support\Carbon $updated_at Timestamp when the product was last updated.
 */
class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'sale_price',
        'quantity',
        'product_category_id',
        'vendor_id',
        'image',
    ];

    /**
     * Get the vendor that owns the product.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    /**
     * Get the category that the product belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    /**
     * Boot the model and set the slug if not provided.
     *
     * This method is called when a product is being created.
     * If no slug is provided, it will generate one based on the product name.
     */
    protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }
}
