<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Complaint extends Model
{
    protected $table = 'complaints';
    protected $primaryKey = 'ComplaintID';

    protected $fillable = [
        'complaint_code',
        'order_id',
        'customer_id',
        'complaint_type',
        'title',
        'content',
        'images',
        'status',
        'priority',
        'assigned_to',
        'resolution',
        'compensation_type',
        'compensation_value',
        'resolved_at',
        'closed_at',
    ];

    protected $casts = [
        'images' => 'json',
        'compensation_value' => 'decimal:2',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'OrderID');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id', 'UserID');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to', 'UserID');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(ComplaintResponse::class, 'complaint_id', 'ComplaintID');
    }
}
