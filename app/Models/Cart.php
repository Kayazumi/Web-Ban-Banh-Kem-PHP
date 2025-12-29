<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    protected $table = 'cart';
    protected $primaryKey = 'CartID';

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'note',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'UserID');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'ProductID');
    }
}
