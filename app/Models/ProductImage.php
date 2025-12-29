<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    protected $table = 'product_images';
    protected $primaryKey = 'ImageID';

    protected $fillable = [
        'product_id',
        'image_url',
        'alt_text',
        'is_primary',
        'display_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'display_order' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'ProductID');
    }
}
