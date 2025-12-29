<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    protected $table = 'contacts';
    protected $primaryKey = 'ContactID';

    protected $fillable = [
        'customer_id',
        'subject',
        'message',
        'status',
        'responded_at',
        'responded_by',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id', 'UserID');
    }

    public function respondedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by', 'UserID');
    }
}
