<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplaintResponse extends Model
{
    protected $table = 'complaint_responses';
    protected $primaryKey = 'ResponseID';

    protected $fillable = [
        'complaint_id',
        'user_id',
        'user_type',
        'content',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'json',
    ];

    public function complaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class, 'complaint_id', 'ComplaintID');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'UserID');
    }
}
