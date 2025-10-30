<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ProductShowcase extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'product_name',
        'brand',
        'description',
        'image_url',
        'image_path',
        'image_filename',
        'image_original_name',
        'price_text',
        'price',
        'currency',
        'rating',
        'review_count',
        'affiliate_url',
        'affiliate_label',
        'merchant',
        'features',
        'pros',
        'cons',
        'sort_order',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'pros' => 'array',
        'cons' => 'array',
        'price' => 'decimal:2',
        'rating' => 'decimal:1',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the post that owns the product showcase.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Scope a query to only include active showcases.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured showcases.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    /**
     * Get the affiliate link associated with this product showcase.
     * Auto-creates AffiliateLink if it doesn't exist.
     */
    public function affiliateLink()
    {
        if (!$this->affiliate_url) {
            return null;
        }

        // Cache the result to avoid multiple queries
        if (!isset($this->_cached_affiliate_link)) {
            // Try to find existing affiliate link with same URL for this post
            $affiliateLink = \App\Models\AffiliateLink::where('url', $this->affiliate_url)
                ->where('post_id', $this->post_id)
                ->first();
            
            if (!$affiliateLink) {
                // Create new affiliate link for tracking
                $affiliateLink = \App\Models\AffiliateLink::create([
                    'slug' => Str::random(8),
                    'label' => $this->affiliate_label ?: $this->product_name,
                    'url' => $this->affiliate_url,
                    'merchant' => $this->merchant,
                    'post_id' => $this->post_id,
                    'enabled' => true,
                    'rel' => 'sponsored nofollow',
                ]);
            }
            
            $this->_cached_affiliate_link = $affiliateLink;
        }
        
        return $this->_cached_affiliate_link;
    }

    /**
     * Get the cloaked affiliate URL for tracking.
     */
    public function getCloakedUrlAttribute()
    {
        $affiliateLink = $this->affiliateLink();
        return $affiliateLink ? route('affiliate.redirect', $affiliateLink->slug) : $this->affiliate_url;
    }

    /**
     * Get the display price.
     */
    public function getDisplayPriceAttribute()
    {
        return $this->price_text ?: ($this->price ? $this->currency . ' ' . number_format($this->price, 2) : '');
    }

    /**
     * Get the product image URL (either uploaded or external URL)
     */
    public function getProductImageUrlAttribute()
    {
        if ($this->image_path) {
            return asset('uploads/products/' . $this->image_path);
        }
        
        return $this->image_url;
    }
}
