<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'CategoryID';

    protected $fillable = [
        'category_name',
        'description',
        'slug',
        'parent_id',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    // Relationships
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id', 'CategoryID');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id', 'CategoryID');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id', 'CategoryID');
    }

    // Scope for active categories
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for root categories (no parent)
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }
}
