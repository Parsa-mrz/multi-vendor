<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Vendor
 *
 * Represents a vendor in the system. A vendor is associated with a user and can have multiple products.
 * The vendor has attributes like store name, description, and active status.
 *
 *
 * @property int $id The unique identifier for the vendor.
 * @property string $store_name The name of the vendor's store.
 * @property string $description A description of the vendor or store.
 * @property bool $is_active Indicates whether the vendor is active or not.
 * @property int $user_id The ID of the user who owns the vendor account.
 * @property \Illuminate\Support\Carbon $created_at Timestamp when the vendor was created.
 * @property \Illuminate\Support\Carbon $updated_at Timestamp when the vendor was last updated.
 */
class Vendor extends Model
{
    /** @use HasFactory<\Database\Factories\VendorFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['store_name', 'description', 'is_active', 'user_id'];

    /**
     * Get the user that owns the vendor.
     *
     * This method defines a one-to-many inverse relationship between the Vendor and User models.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the products associated with the vendor.
     *
     * This method defines a one-to-many relationship between the Vendor and Product models.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'vendor_id');
    }
}
