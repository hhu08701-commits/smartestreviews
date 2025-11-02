<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    /**
     * Display a listing of posts.
     */
    public function index(Request $request)
    {
        $query = Post::published()->with(['author', 'categories', 'tags']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('content', 'like', "%{$searchTerm}%")
                  ->orWhere('excerpt', 'like', "%{$searchTerm}%");
            });
        }

        // Category filter
        if ($request->has('category') && $request->category) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by year and month (for Archives)
        if ($request->has('year') && $request->year) {
            $query->whereYear('published_at', $request->year);
            
            // If month is provided, filter by both year and month
            if ($request->has('month') && $request->month) {
                $query->whereMonth('published_at', intval($request->month));
            }
        }

        // Sort by
        $sortBy = $request->get('sort', 'published_at');
        $sortOrder = $request->get('order', 'desc');
        
        switch ($sortBy) {
            case 'title':
                $query->orderBy('title', $sortOrder);
                break;
            case 'views':
                $query->orderBy('views_count', $sortOrder);
                break;
            case 'rating':
                $query->orderBy('rating', $sortOrder);
                break;
            default:
                $query->orderBy('published_at', $sortOrder);
        }

        $posts = $query->paginate(12);

        // Get categories for filter
        $categories = \App\Models\Category::active()->orderBy('name')->get();

        // Get posts for "You May Have Missed" section (6 posts, excluding current page posts)
        $currentPagePostIds = $posts->pluck('id')->toArray();
        $missedPostsQuery = Post::published()
            ->with(['author', 'categories'])
            ->whereNotIn('id', $currentPagePostIds)
            ->latest('published_at');
        
        // Always get 6 posts, even if we need to include some from current page
        $missedPosts = $missedPostsQuery->limit(6)->get();
        
        // If we don't have 6 posts, fill with random published posts
        if ($missedPosts->count() < 6) {
            $needed = 6 - $missedPosts->count();
            $additionalPosts = Post::published()
                ->with(['author', 'categories'])
                ->whereNotIn('id', array_merge($currentPagePostIds, $missedPosts->pluck('id')->toArray()))
                ->inRandomOrder()
                ->limit($needed)
                ->get();
            
            $missedPosts = $missedPosts->concat($additionalPosts)->take(6);
        }

        return view('posts.index', compact('posts', 'categories', 'missedPosts'));
    }

    /**
     * Display the specified post.
     */
    public function show($year, $month, $slug, Request $request)
    {
        try {
            // Find the post
                    $post = Post::published()
                        ->where('slug', $slug)
                        ->whereYear('published_at', $year)
                        ->whereMonth('published_at', $month)
                        ->with(['author', 'categories', 'tags', 'listItems.affiliateLink', 'faqs', 'affiliateLinks', 'productShowcases'])
                        ->firstOrFail();

            // Increment views count
            $post->incrementViews();

            // Get related posts - simplified to avoid errors
            $relatedPosts = collect([]);

            // Get recent posts from same category - simplified to avoid errors
            $recentCategoryPosts = collect([]);

                    return view('posts.show', compact(
                        'post',
                        'relatedPosts',
                        'recentCategoryPosts'
                    ));
        } catch (\Exception $e) {
            Log::error('PostController error: ' . $e->getMessage());
            abort(404, 'Post not found');
        }
    }
}
