<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BreakingNews extends Model
{
    protected $table = 'breaking_news';
    
    protected $fillable = [
        'title',
        'link',
        'image_url',
        'post_id',
        'affiliate_link_id',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the post associated with this breaking news item.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the affiliate link associated with this breaking news item.
     */
    public function affiliateLink(): BelongsTo
    {
        return $this->belongsTo(AffiliateLink::class);
    }

    /**
     * Scope a query to only include active breaking news.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    /**
     * Get the URL for this breaking news item.
     * Priority: Affiliate Link > Post > Custom Link
     */
    public function getUrlAttribute(): string
    {
        // Priority 1: Affiliate Link
        if ($this->affiliate_link_id && $this->affiliateLink && $this->affiliateLink->enabled) {
            return $this->affiliateLink->cloaked_url;
        }
        
        // Priority 2: Post
        if ($this->post_id && $this->post) {
            $post = $this->post;
            return route('posts.show', [
                $post->published_at->year,
                str_pad($post->published_at->month, 2, '0', STR_PAD_LEFT),
                $post->slug
            ]);
        }
        
        // Priority 3: Custom Link
        return $this->link ?? '#';
    }

    /**
     * Get the display image URL for this breaking news item.
     * Falls back to post featured image if no image_url is set.
     */
    public function getDisplayImageUrlAttribute(): ?string
    {
        if ($this->image_url) {
            return $this->image_url;
        }
        
        // Fallback to post featured image if available
        if ($this->post_id && $this->post && $this->post->featured_image) {
            return $this->post->featured_image;
        }
        
        return null;
    }
}
