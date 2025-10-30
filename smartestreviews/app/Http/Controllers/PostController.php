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

        return view('posts.index', compact('posts', 'categories'));
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
