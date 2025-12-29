<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromotionUsage extends Model
{
    protected $table = 'promotion_usage';
    protected $primaryKey = 'UsageID';

    protected $fillable = [
        'promotion_id',
        'user_id',
        'order_id',
        'discount_amount',
        'used_at',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
        'used_at' => 'datetime',
    ];

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class, 'promotion_id', 'PromotionID');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'UserID');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'OrderID');
    }
}
