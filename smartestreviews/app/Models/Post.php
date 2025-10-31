<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'featured_image_alt',
        'featured_image_caption',
        'image_path',
        'image_filename',
        'image_original_name',
        'author_id',
        'post_type',
        'status',
        'published_at',
        'product_name',
        'brand',
        'rating',
        'pros',
        'cons',
        'price_text',
        'badges',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'views_count',
        'clicks_count',
        'is_featured',
        'featured_order',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'rating' => 'decimal:1',
        'pros' => 'array',
        'cons' => 'array',
        'badges' => 'array',
        'meta_keywords' => 'array',
        'views_count' => 'integer',
        'clicks_count' => 'integer',
        'is_featured' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
            
            // Set default views_count to 2304 if not explicitly set
            // This ensures every new post starts with at least 2304 views
            if ($post->views_count === null) {
                $post->views_count = 2304;
            } elseif ($post->views_count < 2304) {
                // If someone sets a value less than 2304, ensure it's at least 2304
                $post->views_count = 2304;
            }
        });

        static::updating(function ($post) {
            if ($post->isDirty('title') && empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    /**
     * Get the author that owns the post.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the categories for the post.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Get the tags for the post.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Get the affiliate links for the post.
     */
    public function affiliateLinks(): HasMany
    {
        return $this->hasMany(AffiliateLink::class);
    }

    /**
     * Get the list items for the post.
     */
    public function listItems(): HasMany
    {
        return $this->hasMany(ListItem::class)->orderBy('rank');
    }

    /**
     * Get the FAQs for the post.
     */
    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class)->orderBy('sort_order');
    }

    /**
     * Get the product showcases for the post.
     */
    public function productShowcases(): HasMany
    {
        return $this->hasMany(ProductShowcase::class)->active()->ordered();
    }

    /**
     * Get the page views for the post.
     */
    public function pageViews(): HasMany
    {
        return $this->hasMany(PageView::class);
    }

    /**
     * Get the media for the post.
     */
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'model');
    }

    /**
     * Scope a query to only include published posts.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope a query to only include featured posts.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include posts of a specific type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('post_type', $type);
    }

    /**
     * Scope a query to order by published date.
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    /**
     * Scope a query to order by views count.
     */
    public function scopePopular($query)
    {
        return $query->orderBy('views_count', 'desc');
    }

    /**
     * Scope a query to order by rating.
     */
    public function scopeTopRated($query)
    {
        return $query->orderBy('rating', 'desc');
    }

    /**
     * Get related posts based on categories and tags.
     */
    public function relatedPosts($limit = 5)
    {
        return static::published()
            ->where('id', '!=', $this->id)
            ->whereHas('categories', function ($query) {
                $query->whereIn('categories.id', $this->categories->pluck('id'));
            })
            ->orWhereHas('tags', function ($query) {
                $query->whereIn('tags.id', $this->tags->pluck('id'));
            })
            ->limit($limit)
            ->get();
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the URL for the post.
     */
    public function getUrlAttribute()
    {
        return route('posts.show', [
            'year' => $this->published_at->format('Y'),
            'month' => $this->published_at->format('m'),
            'slug' => $this->slug,
        ]);
    }

    /**
     * Get the featured image URL (either uploaded or external URL)
     */
    public function getFeaturedImageUrlAttribute()
    {
        if ($this->image_path) {
            return asset('uploads/posts/' . $this->image_path);
        }
        
        return $this->featured_image;
    }

    /**
     * Get the excerpt or truncated content.
     */
    public function getExcerptAttribute($value)
    {
        if ($value) {
            return $value;
        }

        return Str::limit(strip_tags($this->content), 160);
    }

    /**
     * Get the meta title or fallback to title.
     */
    public function getMetaTitleAttribute($value)
    {
        return $value ?: $this->title;
    }

    /**
     * Get the meta description or fallback to excerpt.
     */
    public function getMetaDescriptionAttribute($value)
    {
        return $value ?: $this->excerpt;
    }

    /**
     * Increment views count.
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Increment clicks count.
     */
    public function incrementClicks()
    {
        $this->increment('clicks_count');
    }
}
