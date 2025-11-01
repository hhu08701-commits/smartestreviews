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
            // Delete old image if exists
            if ($slideshow->image && file_exists(public_path($slideshow->image))) {
                @unlink(public_path($slideshow->image));
            }

            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/slideshow'), $filename);
            $imagePath = '/uploads/slideshow/' . $filename;
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
