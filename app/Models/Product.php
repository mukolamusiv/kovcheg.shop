<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'category_id',
        'cost_per_unit',
        'unit',
        'stock_quantity',
        'photo_path',
        'photos',
    ];

    protected $casts = [
        'photos' => 'array',
    ];

    /**
     * Get the category that the product belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the productions associated with the product.
     */
    public function productions(): HasMany
    {
        return $this->hasMany(Production::class);
    }

    /**
     * Get the movement products associated with the product.
     */
    public function movementProducts(): HasMany
    {
        return $this->hasMany(MoventProduct::class);
    }
}
