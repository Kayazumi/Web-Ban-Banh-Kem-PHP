<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Promotion extends Model
{
    protected $table = 'promotions';
    protected $primaryKey = 'PromotionID';

    protected $fillable = [
        'promotion_code',
        'promotion_name',
        'description',
        'promotion_type',
        'discount_value',
        'min_order_value',
        'max_discount',
        'quantity',
        'used_count',
        'usage_limit_per_user',
        'start_date',
        'end_date',
        'status',
        'image_url',
        'applicable_products',
        'applicable_categories',
        'customer_type',
        'created_by',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_order_value' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'quantity' => 'integer',
        'used_count' => 'integer',
        'usage_limit_per_user' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'applicable_products' => 'json',
        'applicable_categories' => 'json',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'UserID');
    }

    public function promotionUsage(): HasMany
    {
        return $this->hasMany(PromotionUsage::class, 'promotion_id', 'PromotionID');
    }
}
