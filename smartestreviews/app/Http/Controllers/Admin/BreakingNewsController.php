<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BreakingNews;
use App\Models\Post;
use App\Models\AffiliateLink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BreakingNewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Show all breaking news (both active and inactive) in admin
        $breakingNews = BreakingNews::with(['post', 'affiliateLink'])
            ->orderBy('is_active', 'desc') // Show active first
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        $stats = [
            'total' => BreakingNews::count(),
            'active' => BreakingNews::where('is_active', true)->count(),
            'inactive' => BreakingNews::where('is_active', false)->count(),
        ];
        
        return view('admin.breaking-news.index', compact('breakingNews', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $posts = Post::published()
            ->orderBy('published_at', 'desc')
            ->limit(100)
            ->get(['id', 'title', 'published_at']);
            
        return view('admin.breaking-news.create', compact('posts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Conditional validation: if image_upload is provided, don't require image_url as URL
        $rules = [
            'title' => 'required|string|max:255',
            'link' => 'nullable|string|max:2048',
            'image_upload' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // 2MB max to match PHP ini
            'post_id' => 'nullable|exists:posts,id',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ];
        
        // Only validate image_url as URL if no file upload
        if (!$request->hasFile('image_upload')) {
            $rules['image_url'] = 'nullable|url|max:2048';
        } else {
            $rules['image_url'] = 'nullable|string|max:2048';
        }
        
        $request->validate($rules);

        // Handle image upload
        $imageUrl = $request->image_url;
        
        if ($request->hasFile('image_upload')) {
            try {
                $file = $request->file('image_upload');
                
                // Validate file
                if (!$file->isValid()) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['image_upload' => 'File upload không hợp lệ.']);
                }
                
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                
                // Ensure directory exists
                $uploadPath = public_path('uploads/breaking-news');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                // Move file
                if ($file->move($uploadPath, $filename)) {
                    $imageUrl = '/uploads/breaking-news/' . $filename;
                } else {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['image_upload' => 'Không thể lưu file. Vui lòng thử lại.']);
                }
            } catch (\Exception $e) {
                \Log::error('Breaking News image upload error: ' . $e->getMessage());
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['image_upload' => 'Lỗi khi upload ảnh: ' . $e->getMessage()]);
            }
        }

        BreakingNews::create([
            'title' => $request->title,
            'link' => $request->link,
            'image_url' => $imageUrl,
            'post_id' => $request->post_id,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.breaking-news.index')
            ->with('success', 'Breaking News created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BreakingNews $breakingNews)
    {
        $posts = Post::published()
            ->orderBy('published_at', 'desc')
            ->limit(100)
            ->get(['id', 'title', 'published_at']);
            
        $affiliateLinks = AffiliateLink::enabled()
            ->with('post')
            ->orderBy('label')
            ->get(['id', 'label', 'url', 'merchant', 'post_id']);
            
        return view('admin.breaking-news.edit', compact('breakingNews', 'posts', 'affiliateLinks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BreakingNews $breakingNews)
    {
        // If only is_active is being toggled (quick toggle from index page)
        if ($request->has('is_active') && count($request->only(['is_active', '_token', '_method'])) === 3) {
            $breakingNews->update([
                'is_active' => (bool)$request->is_active,
            ]);

            return redirect()->route('admin.breaking-news.index')
                ->with('success', 'Breaking News status updated successfully!');
        }

        // Debug: Log all request data
        \Log::info('=== BREAKING NEWS UPDATE START ===', [
            'breaking_news_id' => $breakingNews->id,
            'request_method' => $request->method(),
            'content_type' => $request->header('Content-Type'),
            'has_file' => $request->hasFile('image_upload'),
            'all_input_keys' => array_keys($request->all()),
            'all_files_keys' => array_keys($request->allFiles()),
        ]);

        // Full update with validation
        // Note: PHP upload_max_filesize is 2MB, so we limit to 2MB (2048 KB)
        $rules = [
            'title' => 'required|string|max:255',
            'link' => 'nullable|string|max:2048',
            'image_upload' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // 2MB max to match PHP ini
            'post_id' => 'nullable|exists:posts,id',
            'affiliate_link_id' => 'nullable|exists:affiliate_links,id',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ];
        
        try {
            $request->validate($rules, [], [
                'title' => 'tiêu đề',
                'link' => 'link',
                'image_upload' => 'ảnh upload',
                'post_id' => 'bài viết',
                'affiliate_link_id' => 'affiliate link',
                'sort_order' => 'thứ tự sắp xếp',
                'is_active' => 'trạng thái',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Breaking News Validation Failed', [
                'errors' => $e->errors(),
                'all_input' => $request->all(),
            ]);
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors());
        }

        // Handle image upload - keep existing image if no new upload
        $imageUrl = $breakingNews->image_url; // Keep existing image by default
        
        // Debug: Log request details
        \Log::info('Breaking News Update Request', [
            'has_file' => $request->hasFile('image_upload'),
            'file_input' => $request->file('image_upload') ? $request->file('image_upload')->getClientOriginalName() : null,
            'all_files' => array_keys($request->allFiles()),
            'content_type' => $request->header('Content-Type'),
            'method' => $request->method(),
        ]);
        
        if ($request->hasFile('image_upload')) {
            try {
                $file = $request->file('image_upload');
                
                \Log::info('Breaking News File Details', [
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'is_valid' => $file->isValid(),
                    'error' => $file->getError(),
                ]);
                
                // Check for upload errors first
                if ($file->getError() !== UPLOAD_ERR_OK) {
                    $errorMessages = [
                        UPLOAD_ERR_INI_SIZE => 'File vượt quá kích thước cho phép của server (upload_max_filesize).',
                        UPLOAD_ERR_FORM_SIZE => 'File vượt quá kích thước cho phép trong form (max: 5MB).',
                        UPLOAD_ERR_PARTIAL => 'File chỉ được upload một phần.',
                        UPLOAD_ERR_NO_FILE => 'Không có file nào được upload.',
                        UPLOAD_ERR_NO_TMP_DIR => 'Thiếu thư mục tạm thời.',
                        UPLOAD_ERR_CANT_WRITE => 'Không thể ghi file vào đĩa.',
                        UPLOAD_ERR_EXTENSION => 'Upload bị dừng bởi extension PHP.'
                    ];
                    $errorMsg = $errorMessages[$file->getError()] ?? 'Lỗi upload không xác định (Error code: ' . $file->getError() . ')';
                    
                    \Log::error('Breaking News Upload Error', ['error_code' => $file->getError(), 'message' => $errorMsg]);
                    
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['image_upload' => $errorMsg]);
                }
                
                // Validate file
                if (!$file->isValid()) {
                    \Log::error('Breaking News: File not valid');
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['image_upload' => 'File upload không hợp lệ.']);
                }
                
                // Check file size (2MB max to match PHP upload_max_filesize)
                $maxSize = 2 * 1024 * 1024; // 2MB
                if ($file->getSize() > $maxSize) {
                    \Log::error('Breaking News: File too large', ['size' => $file->getSize(), 'max' => $maxSize]);
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['image_upload' => 'File quá lớn. Kích thước tối đa là 2MB (do giới hạn của server PHP).']);
                }
                
                // Delete old image if exists (only local files)
                if ($breakingNews->image_url && 
                    Str::startsWith($breakingNews->image_url, '/uploads/breaking-news/') &&
                    file_exists(public_path($breakingNews->image_url))) {
                    @unlink(public_path($breakingNews->image_url));
                    \Log::info('Breaking News: Deleted old image', ['old_image' => $breakingNews->image_url]);
                }
                
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                
                // Ensure directory exists
                $uploadPath = public_path('uploads/breaking-news');
                if (!file_exists($uploadPath)) {
                    if (!mkdir($uploadPath, 0755, true)) {
                        \Log::error('Breaking News: Cannot create upload directory', ['path' => $uploadPath]);
                        return redirect()->back()
                            ->withInput()
                            ->withErrors(['image_upload' => 'Không thể tạo thư mục upload.']);
                    }
                }
                
                // Check if directory is writable
                if (!is_writable($uploadPath)) {
                    \Log::error('Breaking News: Upload directory not writable', ['path' => $uploadPath]);
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['image_upload' => 'Thư mục upload không có quyền ghi.']);
                }
                
                // Move file
                if ($file->move($uploadPath, $filename)) {
                    $imageUrl = '/uploads/breaking-news/' . $filename;
                    \Log::info('Breaking News: Image uploaded successfully', [
                        'new_image_url' => $imageUrl,
                        'file_size' => $file->getSize(),
                        'filename' => $filename
                    ]);
                } else {
                    \Log::error('Breaking News: Failed to move uploaded file', [
                        'upload_path' => $uploadPath,
                        'filename' => $filename
                    ]);
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['image_upload' => 'Không thể lưu file. Vui lòng thử lại.']);
                }
            } catch (\Exception $e) {
                \Log::error('Breaking News image upload exception', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['image_upload' => 'Lỗi khi upload ảnh: ' . $e->getMessage()]);
            }
        } else {
            \Log::info('Breaking News: No file uploaded, keeping existing image', [
                'existing_image_url' => $breakingNews->image_url
            ]);
        }

        // Handle link options - only one should be set (priority: affiliate_link > post > custom link)
        $updateData = [
            'title' => $request->title,
            'image_url' => $imageUrl,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ];
        
        if ($request->filled('affiliate_link_id') && $request->affiliate_link_id) {
            $updateData['affiliate_link_id'] = $request->affiliate_link_id;
            $updateData['post_id'] = null;
            $updateData['link'] = null;
        } elseif ($request->filled('post_id') && $request->post_id) {
            $updateData['post_id'] = $request->post_id;
            $updateData['affiliate_link_id'] = null;
            $updateData['link'] = null;
        } elseif ($request->filled('link') && $request->link) {
            $updateData['link'] = $request->link;
            $updateData['affiliate_link_id'] = null;
            $updateData['post_id'] = null;
        } else {
            // Clear all if nothing selected
            $updateData['affiliate_link_id'] = null;
            $updateData['post_id'] = null;
            $updateData['link'] = null;
        }
        
        $breakingNews->update($updateData);
        
        // Refresh model to get latest data
        $breakingNews->refresh();
        
        \Log::info('Breaking News: Update completed', [
            'id' => $breakingNews->id,
            'title' => $breakingNews->title,
            'image_url' => $breakingNews->image_url,
            'updated_at' => $breakingNews->updated_at,
        ]);

        $message = 'Breaking News updated successfully!';
        if ($request->hasFile('image_upload')) {
            $message = 'Breaking News và ảnh đã được cập nhật thành công!';
        }

        return redirect()->route('admin.breaking-news.index')
            ->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BreakingNews $breakingNews)
    {
        // Delete image if exists
        if ($breakingNews->image_url && 
            Str::startsWith($breakingNews->image_url, '/uploads/') &&
            file_exists(public_path($breakingNews->image_url))) {
            @unlink(public_path($breakingNews->image_url));
        }
        
        $breakingNews->delete();
        
        return redirect()->route('admin.breaking-news.index')
            ->with('success', 'Breaking News deleted successfully!');
    }
}
