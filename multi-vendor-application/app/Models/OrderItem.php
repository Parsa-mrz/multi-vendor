<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderItem
 *
 * Represents an item in an order, linking a product with its price, quantity, and additional options.
 * An order item belongs to both an order and a product.
 *
 *
 * @property int $id
 * @property int $order_id The ID of the order that this item belongs to.
 * @property int $product_id The ID of the product in the order.
 * @property float $price The price of a single unit of the product.
 * @property int $quantity The quantity of the product ordered.
 * @property array $options Additional options associated with the product in the order (e.g., size, color).
 * @property \Illuminate\Support\Carbon $created_at Timestamp when the order item was created.
 * @property \Illuminate\Support\Carbon $updated_at Timestamp when the order item was last updated.
 * @property float $total The total price for the order item, calculated as price * quantity.
 */
class OrderItem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'price',
        'quantity',
        'options',
    ];

    /**
     * The attributes that should be cast to specific types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'options' => 'array',
        'price' => 'float',
    ];

    /**
     * Get the order that owns the order item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product associated with the order item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the total price for the order item.
     * The total is calculated as price * quantity.
     *
     * @return float The total price for the order item.
     */
    public function getTotalAttribute()
    {
        return $this->price * $this->quantity;
    }
}
