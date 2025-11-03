<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotProduct;
use Illuminate\Http\Request;

class HotProductController extends Controller
{
    public function index()
    {
        $hotProducts = HotProduct::orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('admin.hot-products.index', compact('hotProducts'));
    }

    public function create()
    {
        return view('admin.hot-products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'image_url' => 'nullable|url|max:255',
            'rating' => 'nullable|numeric|min:0|max:5',
            'price' => 'nullable|string|max:255',
            'trending' => 'nullable|integer|min:0',
            'url' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $imagePath = $request->image_url;

        // Handle file upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/hot-products'), $filename);
            $imagePath = '/uploads/hot-products/' . $filename;
        }

        HotProduct::create([
            'name' => $request->name,
            'brand' => $request->brand,
            'image' => $imagePath,
            'rating' => $request->rating ?? 0,
            'price' => $request->price ?? '',
            'trending' => $request->trending ?? 0,
            'url' => $request->url,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.hot-products.index')
            ->with('success', 'Hot Product created successfully!');
    }

    public function show(HotProduct $hotProduct)
    {
        return view('admin.hot-products.show', compact('hotProduct'));
    }

    public function edit(HotProduct $hotProduct)
    {
        return view('admin.hot-products.edit', compact('hotProduct'));
    }

    public function update(Request $request, HotProduct $hotProduct)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'image_url' => 'nullable|url|max:255',
            'rating' => 'nullable|numeric|min:0|max:5',
            'price' => 'nullable|string|max:255',
            'trending' => 'nullable|integer|min:0',
            'url' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $imagePath = $hotProduct->image;

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
                
                \Log::error('Hot Product image upload failed', [
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
            $uploadDir = public_path('uploads/hot-products');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            if (!is_writable($uploadDir)) {
                \Log::error('Hot Product upload directory not writable', ['directory' => $uploadDir]);
                return back()->withInput()->withErrors(['image' => 'Không thể ghi vào thư mục upload.']);
            }
            
            // Delete old image if exists
            if ($hotProduct->image && file_exists(public_path($hotProduct->image))) {
                @unlink(public_path($hotProduct->image));
            }

            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $moveResult = $file->move($uploadDir, $filename);
            
            if ($moveResult) {
                $imagePath = '/uploads/hot-products/' . $filename;
                
                \Log::info('Hot Product image uploaded successfully', [
                    'filename' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ]);
            } else {
                \Log::error('Hot Product image move failed', ['filename' => $filename]);
                return back()->withInput()->withErrors(['image' => 'Không thể di chuyển file.']);
            }
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->image_url;
        }

        $hotProduct->update([
            'name' => $request->name,
            'brand' => $request->brand,
            'image' => $imagePath,
            'rating' => $request->rating ?? 0,
            'price' => $request->price ?? '',
            'trending' => $request->trending ?? 0,
            'url' => $request->url,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.hot-products.index')
            ->with('success', 'Hot Product updated successfully!');
    }

    public function destroy(HotProduct $hotProduct)
    {
        $hotProduct->delete();
        return redirect()->route('admin.hot-products.index')
            ->with('success', 'Hot Product deleted successfully!');
    }

    public function updateSort(Request $request, HotProduct $hotProduct)
    {
        $request->validate([
            'sort_order' => 'required|integer|min:0',
        ]);

        $hotProduct->update([
            'sort_order' => $request->sort_order,
        ]);

        return redirect()->route('admin.hot-products.index')
            ->with('success', 'Thứ hạng đã được cập nhật!');
    }
}
