<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\SlideshowSlide;
use App\Models\HotProduct;
use App\Models\BreakingNews;
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
        $limit = 20; // Increased to support blog list section
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

        // Get editor's picks for Editor's Picks section
        $editorsPicks = Post::published()
            ->editorPick()
            ->with(['author', 'categories', 'tags', 'affiliateLinks'])
            ->orderBy('editor_pick_order', 'asc')
            ->orderBy('published_at', 'desc')
            ->limit(10)
            ->get();
        
        // If not enough editor's picks, fill with featured posts that are not already editor picks
        if ($editorsPicks->count() < 10) {
            $needed = 10 - $editorsPicks->count();
            $additionalPicks = Post::published()
                ->where('is_editor_pick', false)
                ->featured()
                ->with(['author', 'categories', 'tags', 'affiliateLinks'])
                ->orderBy('featured_order', 'asc')
                ->orderBy('published_at', 'desc')
                ->limit($needed)
                ->get();
            
            $editorsPicks = $editorsPicks->concat($additionalPicks)->take(10);
        }

        // Get hot products for TRENDING NOW sidebar (from admin Hot Products)
        $trendingHotProducts = HotProduct::active()
            ->ordered()
            ->limit(7)
            ->get();
        
        // Get trending posts for fallback (if needed elsewhere)
        $trendingPosts = Post::published()
            ->trending()
            ->with(['author', 'categories', 'affiliateLinks'])
            ->orderBy('trending_order', 'asc')
            ->orderBy('published_at', 'desc')
            ->limit(7)
            ->get();
        
        // If not enough trending posts, fill with popular posts
        if ($trendingPosts->count() < 7) {
            $needed = 7 - $trendingPosts->count();
            $additionalTrending = Post::published()
                ->where('is_trending', false)
                ->with(['author', 'categories', 'affiliateLinks'])
                ->popular()
                ->limit($needed)
                ->get();
            
            $trendingPosts = $trendingPosts->concat($additionalTrending)->take(7);
        }

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

        // Get breaking news from database - all active items
        $breakingNewsItems = BreakingNews::active()
            ->with(['post', 'affiliateLink'])
            ->ordered()
            ->get();
        
        // If no breaking news, fallback to latest posts (for backward compatibility)
        if ($breakingNewsItems->isEmpty()) {
            $breakingNewsItems = $latestPosts->take(5);
        }

        return view('home', compact(
            'latestPosts',
            'trendingPosts',
            'popularPosts',
            'topRatedPosts',
            'categories',
            'slides',
            'hotProducts',
            'showViewAllButton',
            'editorsPicks',
            'trendingHotProducts',
            'featuredPosts',
            'breakingNewsItems'
        ));
    }
}
