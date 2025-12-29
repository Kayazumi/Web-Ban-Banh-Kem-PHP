<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderStatusHistory extends Model
{
    protected $table = 'order_status_history';
    protected $primaryKey = 'HistoryID';

    protected $fillable = [
        'order_id',
        'old_status',
        'new_status',
        'changed_by',
        'note',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'OrderID');
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by', 'UserID');
    }
}
