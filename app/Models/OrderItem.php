<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'OrderItemID';

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_price',
        'quantity',
        'subtotal',
        'note',
    ];

    protected $casts = [
        'product_price' => 'decimal:2',
        'quantity' => 'integer',
        'subtotal' => 'decimal:2',
    ];

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'OrderID');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'ProductID');
    }
}
