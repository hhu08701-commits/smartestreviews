<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\SlideshowSlide;
use App\Models\HotProduct;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index(Request $request)
    {
        // Get latest posts: featured first, then fill with latest non-featured posts
        $featuredPosts = Post::published()
            ->featured()
            ->with(['author', 'categories', 'tags', 'affiliateLinks'])
            ->orderBy('featured_order', 'asc')
            ->orderBy('published_at', 'desc')
            ->get();
        
        // Get latest non-featured posts to fill remaining slots
        $limit = 12;
        $featuredCount = $featuredPosts->count();
        $remainingSlots = max(0, $limit - $featuredCount);
        
        $nonFeaturedPosts = collect([]);
        if ($remainingSlots > 0) {
            $nonFeaturedPosts = Post::published()
                ->where('is_featured', false)
                ->with(['author', 'categories', 'tags', 'affiliateLinks'])
                ->latest('published_at')
                ->limit($remainingSlots)
                ->get();
        }
        
        // Combine: featured posts first, then non-featured
        $latestPosts = $featuredPosts->concat($nonFeaturedPosts)->take($limit);

        // Get popular posts
        $popularPosts = Post::published()
            ->with(['author', 'categories'])
            ->popular()
            ->limit(5)
            ->get();

        // Get top rated posts
        $topRatedPosts = Post::published()
            ->with(['author', 'categories'])
            ->topRated()
            ->limit(5)
            ->get();

        // Get categories
        $categories = Category::active()
            ->ordered()
            ->withCount('posts')
            ->get();

        // Get slideshow slides from database
        $slides = SlideshowSlide::active()->ordered()->get();
        
        // Get hot products from database (limit to 5 for homepage)
        $hotProducts = HotProduct::active()->ordered()->limit(5)->get();
        
        // Check if there are more hot products (for "View All" button)
        $totalHotProducts = HotProduct::active()->count();
        $showViewAllButton = $totalHotProducts > 5;

        return view('home', compact(
            'latestPosts',
            'popularPosts',
            'topRatedPosts',
            'categories',
            'slides',
            'hotProducts',
            'showViewAllButton'
        ));
    }
}
