<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 *
 * Represents an order placed by a user.
 * An order contains information about the user who placed it, the items in the order,
 * the order's status, payment details, and other related information.
 *
 *
 * @property int $id
 * @property int $user_id The ID of the user who placed the order.
 * @property string $order_number A unique order number.
 * @property string $status The status of the order (e.g., pending, completed).
 * @property float $subtotal The subtotal amount of the order.
 * @property float $total The total amount of the order (including taxes, shipping, etc.).
 * @property string $payment_method The method used for payment (e.g., credit card, PayPal).
 * @property string $payment_status The status of the payment (e.g., pending, paid).
 * @property string|null $notes Additional notes associated with the order.
 * @property string|null $transaction_id The transaction ID from the payment provider.
 * @property \Illuminate\Support\Carbon $created_at Timestamp when the order was created.
 * @property \Illuminate\Support\Carbon $updated_at Timestamp when the order was last updated.
 */
class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'subtotal',
        'total',
        'payment_method',
        'payment_status',
        'notes',
        'transaction_id',
    ];

    /**
     * The "booting" method of the model.
     *
     * Automatically generates an order number when creating a new order.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_number = static::generateOrderNumber();
        });
    }

    /**
     * Generate a unique order number.
     *
     * The order number consists of a prefix based on the current date and a sequential number.
     *
     * @return string The generated order number.
     */
    public static function generateOrderNumber()
    {
        $prefix = 'ORD-'.date('Ymd');
        $lastOrder = static::where('order_number', 'like', $prefix.'%')->latest()->first();

        if ($lastOrder) {
            $number = (int) substr($lastOrder->order_number, -4) + 1;
        } else {
            $number = 1;
        }

        return $prefix.str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get the user who placed the order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items associated with the order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
