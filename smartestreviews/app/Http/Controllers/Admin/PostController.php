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

    public function index()
    {
        try {
            $posts = Post::with(['author', 'categories'])
                ->latest()
                ->paginate(15);

            return view('admin.posts.index', compact('posts'));
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

            return view('admin.posts.create', compact('categories', 'tags', 'users'));
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
            'status' => 'required|in:draft,published',
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
        
        $post->load(['categories', 'tags']);

        return view('admin.posts.edit', compact('post', 'categories', 'tags', 'users'));
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
            'status' => 'required|in:draft,published',
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
            'featured_image' => $imageData['featured_image'] ?? $request->featured_image,
            'featured_image_alt' => $request->featured_image_alt,
            'featured_image_caption' => $request->featured_image_caption,
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