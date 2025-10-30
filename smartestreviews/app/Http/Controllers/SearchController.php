<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Display search results.
     */
    public function index(Request $request)
    {
        $query = $request->get('q');
        $category = $request->get('category');
        $sort = $request->get('sort', 'latest');

        $posts = Post::published()
            ->with(['author', 'categories', 'tags']);

        // Apply search query
        if ($query) {
            $posts->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%")
                  ->orWhere('product_name', 'like', "%{$query}%")
                  ->orWhere('brand', 'like', "%{$query}%");
            });
        }

        // Apply category filter
        if ($category) {
            $posts->whereHas('categories', function ($q) use ($category) {
                $q->where('categories.slug', $category);
            });
        }

        // Apply sorting
        switch ($sort) {
            case 'popular':
                $posts->popular();
                break;
            case 'rating':
                $posts->topRated();
                break;
            default:
                $posts->latest();
                break;
        }

        $posts = $posts->paginate(12);

        // Get categories for filter
        $categories = Category::active()
            ->ordered()
            ->withCount('posts')
            ->get();

        return view('search.index', compact(
            'posts',
            'categories',
            'query',
            'category',
            'sort'
        ));
    }
}
