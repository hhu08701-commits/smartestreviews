<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotProduct extends Model
{
    protected $fillable = [
        'name',
        'brand',
        'image',
        'rating',
        'price',
        'trending',
        'url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
