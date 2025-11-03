<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SlideshowSlide;
use Illuminate\Http\Request;

class SlideshowController extends Controller
{
    public function index()
    {
        $slides = SlideshowSlide::orderBy('sort_order')->paginate(15);
        return view('admin.slideshow.index', compact('slides'));
    }

    public function create()
    {
        return view('admin.slideshow.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'image_url' => 'nullable|url|max:255',
            'category' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $imagePath = $request->image_url;

        // Handle file upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/slideshow'), $filename);
            $imagePath = '/uploads/slideshow/' . $filename;
        }

        SlideshowSlide::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'category' => $request->category,
            'button_text' => $request->button_text ?? 'Read More',
            'url' => $request->url,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.slideshow.index')
            ->with('success', 'Slide created successfully!');
    }

    public function show(SlideshowSlide $slideshow)
    {
        return view('admin.slideshow.show', compact('slideshow'));
    }

    public function edit(SlideshowSlide $slideshow)
    {
        return view('admin.slideshow.edit', compact('slideshow'));
    }

    public function update(Request $request, SlideshowSlide $slideshow)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'image_url' => 'nullable|url|max:255',
            'category' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $imagePath = $slideshow->image;

        // Handle file upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            
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
                
                \Log::error('Slideshow image upload failed', [
                    'error_code' => $errorCode,
                    'error_message' => $errorMessage,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                ]);
                
                return back()->withInput()->withErrors(['image' => $errorMessage]);
            }
            
            // Validate file size (2MB max)
            if ($file->getSize() > 2097152) {
                return back()->withInput()->withErrors(['image' => 'File vượt quá 2MB. Vui lòng chọn file nhỏ hơn.']);
            }
            
            // Ensure upload directory exists
            $uploadDir = public_path('uploads/slideshow');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            if (!is_writable($uploadDir)) {
                \Log::error('Slideshow upload directory not writable', ['directory' => $uploadDir]);
                return back()->withInput()->withErrors(['image' => 'Không thể ghi vào thư mục upload.']);
            }
            
            // Delete old image if exists
            if ($slideshow->image && file_exists(public_path($slideshow->image))) {
                @unlink(public_path($slideshow->image));
            }

            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $moveResult = $file->move($uploadDir, $filename);
            
            if ($moveResult) {
                $imagePath = '/uploads/slideshow/' . $filename;
                
                \Log::info('Slideshow image uploaded successfully', [
                    'filename' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ]);
            } else {
                \Log::error('Slideshow image move failed', ['filename' => $filename]);
                return back()->withInput()->withErrors(['image' => 'Không thể di chuyển file.']);
            }
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->image_url;
        }

        $slideshow->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'category' => $request->category,
            'button_text' => $request->button_text ?? 'Read More',
            'url' => $request->url,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.slideshow.index')
            ->with('success', 'Slide updated successfully!');
    }

    public function destroy(SlideshowSlide $slideshow)
    {
        $slideshow->delete();
        return redirect()->route('admin.slideshow.index')
            ->with('success', 'Slide deleted successfully!');
    }
}
