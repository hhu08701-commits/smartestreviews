<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListItem extends Model
{
    /** @use HasFactory<\Database\Factories\ListItemFactory> */
    use HasFactory;

    protected $fillable = [
        'post_id',
        'rank',
        'product_name',
        'brand',
        'verdict',
        'rating',
        'price_text',
        'image_url',
        'affiliate_link_id',
        'pros',
        'cons',
        'specifications',
        'is_featured',
    ];

    protected $casts = [
        'rank' => 'integer',
        'rating' => 'decimal:1',
        'pros' => 'array',
        'cons' => 'array',
        'specifications' => 'array',
        'is_featured' => 'boolean',
    ];

    /**
     * Get the post that owns the list item.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the affiliate link for the list item.
     */
    public function affiliateLink(): BelongsTo
    {
        return $this->belongsTo(AffiliateLink::class);
    }

    /**
     * Scope a query to order by rank.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('rank');
    }

    /**
     * Scope a query to only include featured items.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
