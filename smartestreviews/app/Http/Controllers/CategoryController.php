<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tag;
use App\Models\Post;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display the specified category.
     */
    public function show(Category $category, Request $request)
    {
        // Get posts for this category
        $posts = Post::published()
            ->whereHas('categories', function ($query) use ($category) {
                $query->where('categories.id', $category->id);
            })
            ->with(['author', 'categories', 'tags'])
            ->latest()
            ->paginate(12);

        // Get related categories
        $relatedCategories = Category::active()
            ->where('id', '!=', $category->id)
            ->withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(6)
            ->get();

        return view('categories.show', compact('category', 'posts', 'relatedCategories'));
    }

    /**
     * Display posts for a specific tag.
     */
    public function showTag(Tag $tag, Request $request)
    {
        // Get posts for this tag
        $posts = Post::published()
            ->whereHas('tags', function ($query) use ($tag) {
                $query->where('tags.id', $tag->id);
            })
            ->with(['author', 'categories', 'tags'])
            ->latest()
            ->paginate(12);

        return view('tags.show', compact('tag', 'posts'));
    }
}
