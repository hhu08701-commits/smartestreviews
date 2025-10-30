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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            // Delete old image if exists
            if ($hotProduct->image && file_exists(public_path($hotProduct->image))) {
                @unlink(public_path($hotProduct->image));
            }

            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/hot-products'), $filename);
            $imagePath = '/uploads/hot-products/' . $filename;
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
