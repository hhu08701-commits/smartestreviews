<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlideshowSlide extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'category',
        'button_text',
        'url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
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
