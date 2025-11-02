<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // Temporarily removed auth middleware for testing
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index(Request $request)
    {
        try {
            $query = Post::with(['author', 'categories', 'tags']);
            
            // Search by title
            if ($request->has('search') && $request->search) {
                $query->where('title', 'like', '%' . $request->search . '%');
            }
            
            // Filter by status
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }
            
            // Filter by post type
            if ($request->has('post_type') && $request->post_type) {
                $query->where('post_type', $request->post_type);
            }
            
            // Filter by featured
            if ($request->has('is_featured')) {
                $query->where('is_featured', $request->is_featured == '1');
            }
            
            // Filter by trending
            if ($request->has('is_trending')) {
                $query->where('is_trending', $request->is_trending == '1');
            }
            
            // Filter by category
            if ($request->has('category_id') && $request->category_id) {
                $query->whereHas('categories', function($q) use ($request) {
                    $q->where('categories.id', $request->category_id);
                });
            }
            
            // Filter by tag
            if ($request->has('tag_id') && $request->tag_id) {
                $query->whereHas('tags', function($q) use ($request) {
                    $q->where('tags.id', $request->tag_id);
                });
            }
            
            // Sort
            $sortBy = $request->get('sort', 'created_at');
            $sortOrder = $request->get('order', 'desc');
            $query->orderBy($sortBy, $sortOrder);
            
            $posts = $query->paginate(15);
            
            // Get filter options
            $categories = Category::where('is_active', true)->orderBy('name')->get();
            $tags = Tag::where('is_active', true)->orderBy('name')->get();
            
            // Stats
            $stats = [
                'total' => Post::count(),
                'published' => Post::where('status', 'published')->count(),
                'draft' => Post::where('status', 'draft')->count(),
                'featured' => Post::where('is_featured', true)->count(),
                'trending' => Post::where('is_trending', true)->count(),
            ];

            return view('admin.posts.index', compact('posts', 'categories', 'tags', 'stats'));
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    public function create()
    {
        try {
            $categories = Category::where('is_active', true)->get();
            $tags = Tag::where('is_active', true)->get();
            $users = User::all();
            $existingAffiliateLinks = \App\Models\AffiliateLink::where('post_id', null)->where('enabled', true)->get();

            return view('admin.posts.create', compact('categories', 'tags', 'users', 'existingAffiliateLinks'));
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    public function store(Request $request)
    {
        // Conditional validation: if image_upload is provided, don't require featured_image URL
        $rules = [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'post_type' => 'required|in:review,list,how-to',
            'status' => 'required|in:draft,published,archived',
            'author_id' => 'required|exists:users,id',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'published_at' => 'nullable|date',
            'featured_image_alt' => 'nullable|string|max:255',
            'featured_image_caption' => 'nullable|string|max:255',
            'image_upload' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_featured' => 'boolean',
            'featured_order' => 'nullable|integer|min:0',
            // Review specific fields
            'product_name' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'rating' => 'nullable|numeric|min:0|max:5',
            'price_text' => 'nullable|string|max:100',
            'pros' => 'nullable|array',
            'cons' => 'nullable|array',
            'badges' => 'nullable|array',
            // SEO fields
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|array',
        ];
        
        // Only validate featured_image as URL if no file upload
        if (!$request->hasFile('image_upload')) {
            $rules['featured_image'] = 'nullable|url|max:2048';
        } else {
            $rules['featured_image'] = 'nullable|string|max:2048';
        }
        
        $request->validate($rules);

        // Handle image upload
        $imageData = [];
        if ($request->hasFile('image_upload')) {
            $file = $request->file('image_upload');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/posts'), $filename);
            
            // Auto-set featured_image URL when uploading file
            $imageUrl = asset('uploads/posts/' . $filename);
            
            $imageData = [
                'image_path' => $filename,
                'image_filename' => $filename,
                'image_original_name' => $file->getClientOriginalName(),
                'featured_image' => $imageUrl,
            ];
        }

        // Auto-set published_at if status is published
        $publishedAt = null;
        if ($request->status === 'published') {
            if ($request->published_at) {
                // If published_at is provided, use it but ensure it's not in the future
                $requestPublishedAt = \Carbon\Carbon::parse($request->published_at);
                $publishedAt = $requestPublishedAt->isFuture() ? now() : $requestPublishedAt;
            } else {
                // If not provided, set to now
                $publishedAt = now();
            }
        } elseif ($request->published_at) {
            $publishedAt = \Carbon\Carbon::parse($request->published_at);
        }

        // Process pros, cons, badges, meta_keywords arrays
        $pros = $request->pros ? (is_array($request->pros) ? $request->pros : array_filter(explode("\n", $request->pros))) : null;
        $cons = $request->cons ? (is_array($request->cons) ? $request->cons : array_filter(explode("\n", $request->cons))) : null;
        $badges = $request->badges ? (is_array($request->badges) ? $request->badges : array_filter(explode("\n", $request->badges))) : null;
        $metaKeywords = $request->meta_keywords ? (is_array($request->meta_keywords) ? $request->meta_keywords : array_filter(explode(',', $request->meta_keywords))) : null;

        $post = Post::create(array_merge([
            'title' => $request->title,
            'slug' => $request->slug ?: Str::slug($request->title),
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'post_type' => $request->post_type,
            'status' => $request->status,
            'author_id' => $request->author_id,
            'published_at' => $publishedAt,
            'featured_image' => $imageData['featured_image'] ?? $request->featured_image,
            'featured_image_alt' => $request->featured_image_alt,
            'featured_image_caption' => $request->featured_image_caption,
            'is_featured' => $request->has('is_featured'),
            'featured_order' => $request->featured_order ?? 0,
            'is_trending' => $request->has('is_trending'),
            'trending_order' => $request->trending_order ?? 0,
            'is_editor_pick' => $request->has('is_editor_pick'),
            'editor_pick_order' => $request->editor_pick_order ?? 0,
            // Review specific
            'product_name' => $request->product_name,
            'brand' => $request->brand,
            'rating' => $request->rating ? round($request->rating, 1) : null,
            'price_text' => $request->price_text,
            'pros' => $pros,
            'cons' => $cons,
            'badges' => $badges,
            // SEO
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $metaKeywords,
        ], $imageData));

        if ($request->categories) {
            $post->categories()->attach($request->categories);
        }

        if ($request->tags) {
            $post->tags()->attach($request->tags);
        }

        return redirect()->route('admin.posts.index')
            ->with('success', 'Bài viết đã được tạo thành công!');
    }

    public function show(Post $post)
    {
        $post->load(['author', 'categories', 'tags', 'affiliateLinks', 'listItems', 'faqs']);
        
        return view('admin.posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $categories = Category::where('is_active', true)->get();
        $tags = Tag::where('is_active', true)->get();
        $users = User::all();
        
        $post->load(['categories', 'tags', 'affiliateLinks']);
        $existingAffiliateLinks = \App\Models\AffiliateLink::where('post_id', null)->where('enabled', true)->get();

        return view('admin.posts.edit', compact('post', 'categories', 'tags', 'users', 'existingAffiliateLinks'));
    }

    public function update(Request $request, Post $post)
    {
        // Conditional validation: if image_upload is provided, don't require featured_image URL
        $rules = [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug,' . $post->id,
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'post_type' => 'required|in:review,list,how-to',
            'status' => 'required|in:draft,published,archived',
            'author_id' => 'required|exists:users,id',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'published_at' => 'nullable|date',
            'featured_image_alt' => 'nullable|string|max:255',
            'featured_image_caption' => 'nullable|string|max:255',
            'image_upload' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_featured' => 'boolean',
            'featured_order' => 'nullable|integer|min:0',
            // Review specific fields
            'product_name' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'rating' => 'nullable|numeric|min:0|max:5',
            'price_text' => 'nullable|string|max:100',
            'pros' => 'nullable|array',
            'cons' => 'nullable|array',
            'badges' => 'nullable|array',
            // SEO fields
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|array',
        ];
        
        // Only validate featured_image as URL if no file upload
        if (!$request->hasFile('image_upload')) {
            $rules['featured_image'] = 'nullable|url|max:2048';
        } else {
            $rules['featured_image'] = 'nullable|string|max:2048';
        }
        
        $request->validate($rules);

        // Handle image upload
        $imageData = [];
        if ($request->hasFile('image_upload')) {
            // Delete old image if exists
            if ($post->image_path && file_exists(public_path('uploads/posts/' . $post->image_path))) {
                @unlink(public_path('uploads/posts/' . $post->image_path));
            }
            
            $file = $request->file('image_upload');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/posts'), $filename);
            
            // Auto-set featured_image URL when uploading file
            $imageUrl = asset('uploads/posts/' . $filename);
            
            $imageData = [
                'image_path' => $filename,
                'image_filename' => $filename,
                'image_original_name' => $file->getClientOriginalName(),
                'featured_image' => $imageUrl,
            ];
        }

        // Auto-set published_at if status is published
        $publishedAt = $post->published_at;
        if ($request->status === 'published' && !$post->published_at) {
            // If status is published but post doesn't have published_at yet, set it
            $publishedAt = $request->published_at ? \Carbon\Carbon::parse($request->published_at) : now();
            // If published_at is in the future, set to now
            if ($publishedAt->isFuture()) {
                $publishedAt = now();
            }
        } elseif ($request->status === 'published' && $post->published_at) {
            // If status is published and post has published_at, only update if provided
            if ($request->published_at) {
                $requestPublishedAt = \Carbon\Carbon::parse($request->published_at);
                $publishedAt = $requestPublishedAt->isFuture() ? now() : $requestPublishedAt;
            }
        } elseif ($request->published_at) {
            $publishedAt = \Carbon\Carbon::parse($request->published_at);
        }

        // Process pros, cons, badges, meta_keywords arrays
        $pros = $request->pros ? (is_array($request->pros) ? $request->pros : array_filter(explode("\n", $request->pros))) : null;
        $cons = $request->cons ? (is_array($request->cons) ? $request->cons : array_filter(explode("\n", $request->cons))) : null;
        $badges = $request->badges ? (is_array($request->badges) ? $request->badges : array_filter(explode("\n", $request->badges))) : null;
        $metaKeywords = $request->meta_keywords ? (is_array($request->meta_keywords) ? $request->meta_keywords : array_filter(explode(',', $request->meta_keywords))) : null;

        $post->update(array_merge([
            'title' => $request->title,
            'slug' => $request->slug ?: Str::slug($request->title),
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'post_type' => $request->post_type,
            'status' => $request->status,
            'author_id' => $request->author_id,
            'published_at' => $publishedAt,
            'is_featured' => $request->has('is_featured'),
            'featured_order' => $request->featured_order ?? 0,
            'is_trending' => $request->has('is_trending'),
            'trending_order' => $request->trending_order ?? 0,
            'is_editor_pick' => $request->has('is_editor_pick'),
            'editor_pick_order' => $request->editor_pick_order ?? 0,
            'featured_image' => $imageData['featured_image'] ?? $request->featured_image,
            'featured_image_alt' => $request->featured_image_alt,
            'featured_image_caption' => $request->featured_image_caption,
            // Review specific
            'product_name' => $request->product_name,
            'brand' => $request->brand,
            'rating' => $request->rating ? round($request->rating, 1) : null,
            'price_text' => $request->price_text,
            'pros' => $pros,
            'cons' => $cons,
            'badges' => $badges,
            // SEO
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $metaKeywords,
        ], $imageData));

        if ($request->categories) {
            $post->categories()->sync($request->categories);
        }

        if ($request->tags) {
            $post->tags()->sync($request->tags);
        }

        return redirect()->route('admin.posts.index')
            ->with('success', 'Bài viết đã được cập nhật thành công!');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', 'Bài viết đã được xóa thành công!');
    }
}