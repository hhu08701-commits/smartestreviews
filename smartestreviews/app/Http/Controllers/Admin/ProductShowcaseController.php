<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductShowcase;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductShowcaseController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductShowcase::with('post');

        // Filter by post if provided
        if ($request->has('post_id') && $request->post_id) {
            $query->where('post_id', $request->post_id);
        }

        $productShowcases = $query->latest()->paginate(15)->appends($request->query());

        // Get posts for filter dropdown
        $posts = \App\Models\Post::where('status', 'published')
            ->orderBy('title')
            ->get();

        return view('admin.product-showcases.index', compact('productShowcases', 'posts'));
    }

    public function create(Request $request)
    {
        $posts = Post::where('status', 'published')->get();
        $selectedPostId = $request->get('post_id');
        return view('admin.product-showcases.create', compact('posts', 'selectedPostId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'product_name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url|max:2048',
            'image_upload' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'price_text' => 'nullable|string|max:50',
            'price' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'rating' => 'nullable|numeric|min:0|max:5',
            'review_count' => 'nullable|integer|min:0',
            'affiliate_url' => 'required|url|max:2048',
            'affiliate_label' => 'nullable|string|max:255',
            'merchant' => 'nullable|string|max:255',
            'features' => 'nullable|string',
            'pros' => 'nullable|string',
            'cons' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        $imageData = [];
        if ($request->hasFile('image_upload')) {
            $file = $request->file('image_upload');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/products'), $filename);
            
            $imageData = [
                'image_path' => $filename,
                'image_filename' => $filename,
                'image_original_name' => $file->getClientOriginalName(),
            ];
        }

        // Convert text fields to arrays
        $data = $request->except('image_upload');
        $data['features'] = $request->features ? array_filter(explode("\n", $request->features)) : null;
        $data['pros'] = $request->pros ? array_filter(explode("\n", $request->pros)) : null;
        $data['cons'] = $request->cons ? array_filter(explode("\n", $request->cons)) : null;
        
        // Ensure review_count has a default value if not provided
        if (!isset($data['review_count']) || $data['review_count'] === null || $data['review_count'] === '') {
            $data['review_count'] = 0;
        }

        $productShowcase = ProductShowcase::create(array_merge($data, $imageData));

        return redirect()->route('admin.product-showcases.index', ['post_id' => $productShowcase->post_id])
            ->with('success', 'Product showcase created successfully! Bạn có thể tiếp tục thêm sản phẩm khác vào post này.');
    }

    public function show(ProductShowcase $productShowcase)
    {
        $productShowcase->load('post');
        return view('admin.product-showcases.show', compact('productShowcase'));
    }

    public function edit(ProductShowcase $productShowcase)
    {
        $posts = Post::where('status', 'published')->get();
        return view('admin.product-showcases.edit', compact('productShowcase', 'posts'));
    }

    public function update(Request $request, ProductShowcase $productShowcase)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'product_name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url|max:2048',
            'image_upload' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'price_text' => 'nullable|string|max:50',
            'price' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'rating' => 'nullable|numeric|min:0|max:5',
            'review_count' => 'nullable|integer|min:0',
            'affiliate_url' => 'required|url|max:2048',
            'affiliate_label' => 'nullable|string|max:255',
            'merchant' => 'nullable|string|max:255',
            'features' => 'nullable|string',
            'pros' => 'nullable|string',
            'cons' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        $imageData = [];
        if ($request->hasFile('image_upload')) {
            // Delete old image if exists
            if ($productShowcase->image_path && file_exists(public_path('uploads/products/' . $productShowcase->image_path))) {
                unlink(public_path('uploads/products/' . $productShowcase->image_path));
            }
            
            $file = $request->file('image_upload');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/products'), $filename);
            
            $imageData = [
                'image_path' => $filename,
                'image_filename' => $filename,
                'image_original_name' => $file->getClientOriginalName(),
            ];
        }

        // Convert text fields to arrays
        $data = $request->except('image_upload');
        $data['features'] = $request->features ? array_filter(explode("\n", $request->features)) : null;
        $data['pros'] = $request->pros ? array_filter(explode("\n", $request->pros)) : null;
        $data['cons'] = $request->cons ? array_filter(explode("\n", $request->cons)) : null;
        
        // Ensure review_count has a default value if not provided
        if (!isset($data['review_count']) || $data['review_count'] === null || $data['review_count'] === '') {
            $data['review_count'] = $productShowcase->review_count ?? 0;
        }

        $productShowcase->update(array_merge($data, $imageData));

        return redirect()->route('admin.product-showcases.index')
            ->with('success', 'Product showcase updated successfully!');
    }

    public function destroy(ProductShowcase $productShowcase)
    {
        $productShowcase->delete();
        return redirect()->route('admin.product-showcases.index')
            ->with('success', 'Product showcase deleted successfully!');
    }
}
