<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class AffiliateLink extends Model
{
    /** @use HasFactory<\Database\Factories\AffiliateLinkFactory> */
    use HasFactory;

    protected $fillable = [
        'slug',
        'label',
        'url',
        'merchant',
        'rel',
        'product_id',
        'post_id',
        'enabled',
        'clicks_count',
        'last_clicked_at',
        'utm_params',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'clicks_count' => 'integer',
        'last_clicked_at' => 'datetime',
        'utm_params' => 'array',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($link) {
            if (empty($link->slug)) {
                $link->slug = Str::random(8);
            }
        });
    }

    /**
     * Get the product that owns the affiliate link.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the post that owns the affiliate link.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the click logs for the affiliate link.
     */
    public function clickLogs(): HasMany
    {
        return $this->hasMany(ClickLog::class);
    }

    /**
     * Scope a query to only include enabled links.
     */
    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    /**
     * Scope a query to filter by merchant.
     */
    public function scopeByMerchant($query, $merchant)
    {
        return $query->where('merchant', $merchant);
    }

    /**
     * Get the cloaked URL.
     */
    public function getCloakedUrlAttribute()
    {
        return route('affiliate.redirect', $this->slug);
    }

    /**
     * Increment clicks count and update last clicked timestamp.
     */
    public function incrementClicks()
    {
        $this->increment('clicks_count');
        $this->update(['last_clicked_at' => now()]);
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
