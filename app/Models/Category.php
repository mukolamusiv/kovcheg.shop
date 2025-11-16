<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'type',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = \Str::slug($category->name);
            }
        });
    }


    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function getParentNameAttribute(): ?string
    {
        return $this->parent?->name;
    }

    // public function parent_name(): ?string
    // {
    //      return $this->parent?->name;
    // }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }
}
