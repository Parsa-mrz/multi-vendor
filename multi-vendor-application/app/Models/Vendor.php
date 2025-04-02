<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Class Vendor
 *
 * Represents a vendor in the system, associated with a user and a profile.
 * A vendor can have a store name, description, and an active status.
 * The Vendor model supports polymorphic relationships with profiles.
 *
 * @package App\Models
 */
class Vendor extends Model
{
    /** @use HasFactory<\Database\Factories\VendorFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['store_name', 'description','is_active','user_id'];

    /**
     * Get the user that owns the vendor.
     *
     * This method defines a one-to-many inverse relationship between the Vendor and User models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
