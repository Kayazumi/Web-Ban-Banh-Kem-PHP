<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'ProductID';

    protected $fillable = [
        'product_name',
        'category_id',
        'description',
        'price',
        'original_price',
        'quantity',
        'unit',
        'status',
        'image_url',
        'weight',
        'ingredients',
        'allergens',
        'shelf_life',
        'storage_conditions',
        'short_intro',
        'short_paragraph',
        'structure',
        'usage',
        'bonus',
        'views',
        'sold_count',
        'is_featured',
        'is_new',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'quantity' => 'integer',
        'shelf_life' => 'integer',
        'views' => 'integer',
        'sold_count' => 'integer',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'CategoryID');
    }

    public function productImages(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'ProductID');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'product_id', 'ProductID');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'product_id', 'ProductID');
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(Cart::class, 'product_id', 'ProductID');
    }

    public function wishlistItems(): HasMany
    {
        return $this->hasMany(Wishlist::class, 'product_id', 'ProductID');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')->where('quantity', '>', 0);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeNew($query)
    {
        return $query->where('is_new', true);
    }

    // Accessors
    public function getDiscountPercentageAttribute()
    {
        if ($this->original_price && $this->original_price > $this->price) {
            return round((($this->original_price - $this->price) / $this->original_price) * 100);
        }
        return 0;
    }

    public function getIsOnSaleAttribute()
    {
        return $this->original_price && $this->original_price > $this->price;
    }

    public function getMainImageAttribute()
    {
        $primaryImage = $this->productImages()->where('is_primary', true)->first();
        return $primaryImage ? $primaryImage->image_url : $this->image_url;
    }
}
