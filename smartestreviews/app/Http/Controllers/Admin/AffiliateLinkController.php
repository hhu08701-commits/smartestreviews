<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffiliateLink;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Request;

class AffiliateLinkController extends Controller
{
    // Temporarily removed auth middleware for testing
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        $affiliateLinks = AffiliateLink::with(['post', 'product'])
            ->latest()
            ->paginate(15);

        // Thống kê affiliate links
        $stats = [
            'total' => AffiliateLink::count(),
            'enabled' => AffiliateLink::where('enabled', true)->count(),
            'disabled' => AffiliateLink::where('enabled', false)->count(),
            'total_clicks' => AffiliateLink::sum('clicks_count'),
            'with_posts' => AffiliateLink::whereNotNull('post_id')->count(),
            'without_posts' => AffiliateLink::whereNull('post_id')->count(),
        ];

        return view('admin.affiliate-links.index', compact('affiliateLinks', 'stats'));
    }

    public function create(Request $request)
    {
        $posts = Post::where('status', 'published')->get();
        $products = Product::where('is_active', true)->get();
        
        // If post_id is provided in query, pre-select that post
        $selectedPostId = $request->get('post_id');

        return view('admin.affiliate-links.create', compact('posts', 'products', 'selectedPostId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|url',
            'merchant' => 'required|string|max:100',
            'post_id' => 'nullable|exists:posts,id',
            'product_id' => 'nullable|exists:products,id',
            'enabled' => 'boolean',
            'utm_params' => 'nullable|json',
        ]);

        $affiliateLink = AffiliateLink::create([
            'label' => $request->label,
            'url' => $request->url,
            'merchant' => $request->merchant,
            'post_id' => $request->post_id,
            'product_id' => $request->product_id,
            'enabled' => $request->enabled ?? true,
            'utm_params' => $request->utm_params ? json_decode($request->utm_params, true) : null,
        ]);

        return redirect()->route('admin.affiliate-links.index')
            ->with('success', 'Affiliate link đã được tạo thành công!');
    }

    public function show(AffiliateLink $affiliateLink)
    {
        $affiliateLink->load(['post', 'product', 'clickLogs']);
        
        return view('admin.affiliate-links.show', compact('affiliateLink'));
    }

    public function edit(AffiliateLink $affiliateLink)
    {
        $posts = Post::where('status', 'published')->get();
        $products = Product::where('is_active', true)->get();

        return view('admin.affiliate-links.edit', compact('affiliateLink', 'posts', 'products'));
    }

    public function update(Request $request, AffiliateLink $affiliateLink)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|url',
            'merchant' => 'required|string|max:100',
            'post_id' => 'nullable|exists:posts,id',
            'product_id' => 'nullable|exists:products,id',
            'enabled' => 'boolean',
            'utm_params' => 'nullable|json',
        ]);

        $affiliateLink->update([
            'label' => $request->label,
            'url' => $request->url,
            'merchant' => $request->merchant,
            'post_id' => $request->post_id,
            'product_id' => $request->product_id,
            'enabled' => $request->enabled ?? true,
            'utm_params' => $request->utm_params ? json_decode($request->utm_params, true) : null,
        ]);

        return redirect()->route('admin.affiliate-links.index')
            ->with('success', 'Affiliate link đã được cập nhật thành công!');
    }

    public function destroy(AffiliateLink $affiliateLink)
    {
        $affiliateLink->delete();

        return redirect()->route('admin.affiliate-links.index')
            ->with('success', 'Affiliate link đã được xóa thành công!');
    }
}