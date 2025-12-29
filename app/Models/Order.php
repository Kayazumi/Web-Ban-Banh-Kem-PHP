<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'OrderID';

    protected $fillable = [
        'order_code',
        'customer_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'shipping_address',
        'ward',
        'district',
        'city',
        'total_amount',
        'discount_amount',
        'shipping_fee',
        'final_amount',
        'payment_method',
        'payment_status',
        'order_status',
        'note',
        'cancel_reason',
        'delivery_date',
        'delivery_time',
        'promotion_code',
        'staff_id',
        'confirmed_at',
        'completed_at',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'delivery_date' => 'date',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id', 'UserID');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id', 'UserID');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'OrderID');
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class, 'order_id', 'OrderID');
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'order_id', 'OrderID');
    }

    public function promotionUsage(): HasMany
    {
        return $this->hasMany(PromotionUsage::class, 'order_id', 'OrderID');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'order_id', 'OrderID');
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('order_status', $status);
    }

    public function scopeByPaymentStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('order_status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('order_status', 'delivery_successful');
    }

    // Accessors
    public function getStatusColorAttribute()
    {
        return match($this->order_status) {
            'pending' => 'warning',
            'order_received' => 'info',
            'preparing' => 'primary',
            'delivering' => 'info',
            'delivery_successful' => 'success',
            'delivery_failed' => 'danger',
            default => 'secondary'
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->order_status) {
            'pending' => 'Chờ xác nhận',
            'order_received' => 'Đã nhận đơn',
            'preparing' => 'Đang chuẩn bị',
            'delivering' => 'Đang giao hàng',
            'delivery_successful' => 'Giao thành công',
            'delivery_failed' => 'Giao thất bại',
            default => 'Không xác định'
        };
    }
}
