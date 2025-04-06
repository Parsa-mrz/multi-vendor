<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'subtotal',
        'total',
        'payment_method',
        'payment_status',
        'notes',
        'transaction_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_number = static::generateOrderNumber();
        });
    }

    /**
     * Generate a unique order number
     */
    public static function generateOrderNumber()
    {
        $prefix = 'ORD-' . date('Ymd');
        $lastOrder = static::where('order_number', 'like', $prefix . '%')->latest()->first();

        if ($lastOrder) {
            $number = (int) substr($lastOrder->order_number, -4) + 1;
        } else {
            $number = 1;
        }

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

}
