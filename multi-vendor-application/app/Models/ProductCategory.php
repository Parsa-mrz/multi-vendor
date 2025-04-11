<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Class ProductCategory
 *
 * Represents a product category in the system. It has attributes like name and slug, and can be associated with many products.
 *
 *
 * @property int $id The unique identifier for the category.
 * @property string $name The name of the category.
 * @property string $slug A URL-friendly version of the category name.
 * @property \Illuminate\Support\Carbon $created_at Timestamp when the category was created.
 * @property \Illuminate\Support\Carbon $updated_at Timestamp when the category was last updated.
 */
class ProductCategory extends Model
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
    ];

    /**
     * Get the products that belong to the category.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'product_category_id');
    }

    /**
     * Boot the model and set the slug if not provided.
     *
     * This method is called when a product category is being created.
     * If no slug is provided, it will generate one based on the category name.
     */
    protected static function booted()
    {
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
}
