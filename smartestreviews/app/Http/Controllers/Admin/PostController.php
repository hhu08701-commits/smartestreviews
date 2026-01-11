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
            
            // Check for upload errors
            if (!$file->isValid()) {
                $errorMessages = [
                    UPLOAD_ERR_INI_SIZE => 'File vượt quá giới hạn upload_max_filesize trong PHP config (2MB).',
                    UPLOAD_ERR_FORM_SIZE => 'File vượt quá giới hạn MAX_FILE_SIZE trong form.',
                    UPLOAD_ERR_PARTIAL => 'File chỉ được upload một phần.',
                    UPLOAD_ERR_NO_FILE => 'Không có file nào được upload.',
                    UPLOAD_ERR_NO_TMP_DIR => 'Thiếu thư mục tạm.',
                    UPLOAD_ERR_CANT_WRITE => 'Không thể ghi file vào disk.',
                    UPLOAD_ERR_EXTENSION => 'Upload bị dừng bởi PHP extension.',
                ];
                
                $errorCode = $file->getError();
                $errorMessage = $errorMessages[$errorCode] ?? 'Lỗi upload không xác định.';
                
                \Log::error('Post image upload failed (create)', [
                    'error_code' => $errorCode,
                    'error_message' => $errorMessage,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                ]);
                
                return back()->withInput()->withErrors(['image_upload' => $errorMessage]);
            }
            
            // Validate file size (2MB max)
            if ($file->getSize() > 2097152) {
                return back()->withInput()->withErrors(['image_upload' => 'File vượt quá 2MB. Vui lòng chọn file nhỏ hơn.']);
            }
            
            // Ensure upload directory exists
            $uploadDir = public_path('uploads/posts');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            if (!is_writable($uploadDir)) {
                \Log::error('Post upload directory not writable (create)', ['directory' => $uploadDir]);
                return back()->withInput()->withErrors(['image_upload' => 'Không thể ghi vào thư mục upload.']);
            }
            
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $moveResult = $file->move($uploadDir, $filename);
            
            if ($moveResult) {
                // Auto-set featured_image URL when uploading file
                $imageUrl = asset('uploads/posts/' . $filename);
                
                $imageData = [
                    'image_path' => $filename,
                    'image_filename' => $filename,
                    'image_original_name' => $file->getClientOriginalName(),
                    'featured_image' => $imageUrl,
                ];
                
                \Log::info('Post image uploaded successfully (create)', [
                    'filename' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ]);
            } else {
                \Log::error('Post image move failed (create)', ['filename' => $filename]);
                return back()->withInput()->withErrors(['image_upload' => 'Không thể di chuyển file.']);
            }
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

        // Convert line breaks to <br> tags for content
        $content = $request->content;
        if ($content) {
            // Convert \n to <br> tags, escape HTML để tránh XSS
            $content = nl2br(e($content));
        }

        $post = Post::create(array_merge([
            'title' => $request->title,
            'slug' => $request->slug ?: Str::slug($request->title),
            'excerpt' => $request->excerpt,
            'content' => $content,
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

        // Convert <br> tags back to \n for textarea editing
        if ($post->content) {
            $post->content = str_replace(['<br>', '<br/>', '<br />'], "\n", $post->content);
            // Decode HTML entities
            $post->content = html_entity_decode($post->content, ENT_QUOTES, 'UTF-8');
        }

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
            // pros, cons, badges can be string (from textarea) or array - we'll convert later
            'pros' => 'nullable',
            'cons' => 'nullable',
            'badges' => 'nullable',
            // SEO fields
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable',
            // featured_image can be URL or local path - not strict validation
            'featured_image' => 'nullable|string|max:2048',
        ];
        
        try {
            $request->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Post update validation failed', [
                'errors' => $e->errors(),
                'post_id' => $post->id,
            ]);
            return back()->withInput()->withErrors($e->errors());
        }

        // Handle image upload
        $imageData = [];
        if ($request->hasFile('image_upload')) {
            $file = $request->file('image_upload');
            
            \Log::info('Post image upload attempt', [
                'post_id' => $post->id,
                'has_file' => $request->hasFile('image_upload'),
                'file_name' => $file ? $file->getClientOriginalName() : 'null',
                'file_size' => $file ? $file->getSize() : 0,
            ]);
            
            // Check for upload errors
            if (!$file->isValid()) {
                $errorMessages = [
                    UPLOAD_ERR_INI_SIZE => 'File vượt quá giới hạn upload_max_filesize trong PHP config (2MB).',
                    UPLOAD_ERR_FORM_SIZE => 'File vượt quá giới hạn MAX_FILE_SIZE trong form.',
                    UPLOAD_ERR_PARTIAL => 'File chỉ được upload một phần.',
                    UPLOAD_ERR_NO_FILE => 'Không có file nào được upload.',
                    UPLOAD_ERR_NO_TMP_DIR => 'Thiếu thư mục tạm.',
                    UPLOAD_ERR_CANT_WRITE => 'Không thể ghi file vào disk.',
                    UPLOAD_ERR_EXTENSION => 'Upload bị dừng bởi PHP extension.',
                ];
                
                $errorCode = $file->getError();
                $errorMessage = $errorMessages[$errorCode] ?? 'Lỗi upload không xác định.';
                
                \Log::error('Post image upload failed', [
                    'error_code' => $errorCode,
                    'error_message' => $errorMessage,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'post_id' => $post->id,
                ]);
                
                return back()->withInput()->withErrors(['image_upload' => $errorMessage]);
            }
            
            // Validate file size (2MB max)
            if ($file->getSize() > 2097152) {
                return back()->withInput()->withErrors(['image_upload' => 'File vượt quá 2MB. Vui lòng chọn file nhỏ hơn.']);
            }
            
            // Ensure upload directory exists
            $uploadDir = public_path('uploads/posts');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            if (!is_writable($uploadDir)) {
                \Log::error('Post upload directory not writable', ['directory' => $uploadDir]);
                return back()->withInput()->withErrors(['image_upload' => 'Không thể ghi vào thư mục upload.']);
            }
            
            // Delete old image if exists
            if ($post->image_path && file_exists(public_path('uploads/posts/' . $post->image_path))) {
                @unlink(public_path('uploads/posts/' . $post->image_path));
                \Log::info('Old post image deleted', ['old_path' => $post->image_path]);
            }
            
            // Get file info BEFORE moving (because after move, temp file is gone)
            $originalName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            
            $moveResult = $file->move($uploadDir, $filename);
            
            if ($moveResult) {
                // Auto-set featured_image URL when uploading file
                $imageUrl = asset('uploads/posts/' . $filename);
                
                $imageData = [
                    'image_path' => $filename,
                    'image_filename' => $filename,
                    'image_original_name' => $originalName,
                    'featured_image' => $imageUrl,
                ];
                
                \Log::info('Post image uploaded successfully', [
                    'filename' => $filename,
                    'original_name' => $originalName,
                    'size' => $fileSize,
                    'post_id' => $post->id,
                    'image_url' => $imageUrl,
                ]);
            } else {
                \Log::error('Post image move failed', ['filename' => $filename, 'post_id' => $post->id]);
                return back()->withInput()->withErrors(['image_upload' => 'Không thể di chuyển file.']);
            }
        } elseif ($request->filled('featured_image') && filter_var($request->featured_image, FILTER_VALIDATE_URL)) {
            // If user provides a new URL (not upload), clear local image fields
            $imageData = [
                'image_path' => null,
                'image_filename' => null,
                'image_original_name' => null,
            ];
            
            // Delete old local image if exists
            if ($post->image_path && file_exists(public_path('uploads/posts/' . $post->image_path))) {
                @unlink(public_path('uploads/posts/' . $post->image_path));
                \Log::info('Old post local image deleted (switching to URL)', ['old_path' => $post->image_path]);
            }
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
        // Convert string from textarea to array (split by newlines) or keep as array if already array
        $pros = $request->filled('pros') ? (is_array($request->pros) ? array_filter($request->pros) : array_filter(explode("\n", trim($request->pros)))) : null;
        $cons = $request->filled('cons') ? (is_array($request->cons) ? array_filter($request->cons) : array_filter(explode("\n", trim($request->cons)))) : null;
        $badges = $request->filled('badges') ? (is_array($request->badges) ? array_filter($request->badges) : array_filter(explode("\n", trim($request->badges)))) : null;
        $metaKeywords = $request->filled('meta_keywords') ? (is_array($request->meta_keywords) ? array_filter($request->meta_keywords) : array_filter(array_map('trim', explode(',', $request->meta_keywords)))) : null;

        // Convert line breaks to <br> tags for content
        $content = $request->content;
        if ($content) {
            // Convert \n to <br> tags
            $content = nl2br(e($content));
        }

        try {
            $updateData = array_merge([
                'title' => $request->title,
                'slug' => $request->slug ?: Str::slug($request->title),
                'excerpt' => $request->excerpt,
                'content' => $content,
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
                'featured_image' => $imageData['featured_image'] ?? ($request->filled('featured_image') ? $request->featured_image : $post->featured_image),
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
            ], $imageData);
            
            \Log::info('Updating post', [
                'post_id' => $post->id,
                'has_image_data' => !empty($imageData),
                'image_path' => $updateData['image_path'] ?? null,
                'featured_image' => $updateData['featured_image'] ?? null,
            ]);
            
            $post->update($updateData);
            
            \Log::info('Post update completed', [
                'post_id' => $post->id,
                'updated_at' => $post->updated_at,
            ]);
        } catch (\Exception $e) {
            \Log::error('Post update failed', [
                'post_id' => $post->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return back()->withInput()->withErrors(['update' => 'Có lỗi xảy ra khi cập nhật: ' . $e->getMessage()]);
        }

        if ($request->categories) {
            $post->categories()->sync($request->categories);
        }

        if ($request->tags) {
            $post->tags()->sync($request->tags);
        }
        
        // Refresh model to get latest data
        $post->refresh();
        
        \Log::info('Post updated successfully', [
            'post_id' => $post->id,
            'has_image_path' => !empty($post->image_path),
            'featured_image' => $post->featured_image,
        ]);

        return redirect()->route('admin.posts.edit', $post)
            ->with('success', 'Bài viết đã được cập nhật thành công!' . (!empty($imageData) && isset($imageData['image_path']) ? ' Ảnh đã được cập nhật.' : ''));
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', 'Bài viết đã được xóa thành công!');
    }
}