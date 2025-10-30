<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\AffiliateLink;
use App\Models\PageView;
use App\Models\ClickLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Temporarily removed auth middleware for testing
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function dashboard()
    {
        try {
            // Basic stats
            $stats = [
                'total_posts' => Post::count(),
                'published_posts' => Post::where('status', 'published')->count(),
                'draft_posts' => Post::where('status', 'draft')->count(),
                'total_categories' => Category::count(),
                'total_affiliate_links' => AffiliateLink::count(),
                'active_affiliate_links' => AffiliateLink::where('enabled', true)->count(),
            ];

            // Analytics - Last 30 days
            $last30Days = now()->subDays(30);
            $last7Days = now()->subDays(7);
            
            // Page views analytics
            $pageViewsData = PageView::where('created_at', '>=', $last30Days)
                ->selectRaw('DATE(created_at) as date, COUNT(*) as views')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->pluck('views', 'date');
            
            // Affiliate clicks analytics
            $affiliateClicksData = ClickLog::where('created_at', '>=', $last30Days)
                ->selectRaw('DATE(created_at) as date, COUNT(*) as clicks')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->pluck('clicks', 'date');
            
            // Top posts by views (last 7 days)
            $topPosts = Post::withCount(['pageViews' => function($query) use ($last7Days) {
                    $query->where('created_at', '>=', $last7Days);
                }])
                ->get()
                ->filter(function($post) {
                    return $post->page_views_count > 0;
                })
                ->sortByDesc('page_views_count')
                ->take(5)
                ->values();
            
            // Top affiliate links by clicks (last 7 days)
            $topAffiliateLinks = AffiliateLink::withCount(['clickLogs' => function($query) use ($last7Days) {
                    $query->where('created_at', '>=', $last7Days);
                }])
                ->get()
                ->filter(function($link) {
                    return $link->click_logs_count > 0;
                })
                ->sortByDesc('click_logs_count')
                ->take(5)
                ->values();
            
            // Device type stats
            $deviceStats = PageView::where('created_at', '>=', $last7Days)
                ->select('device_type', DB::raw('COUNT(*) as count'))
                ->whereNotNull('device_type')
                ->groupBy('device_type')
                ->get()
                ->pluck('count', 'device_type');
            
            // Total stats
            $analytics = [
                'total_views_30d' => PageView::where('created_at', '>=', $last30Days)->count(),
                'total_clicks_30d' => ClickLog::where('created_at', '>=', $last30Days)->count(),
                'total_views_7d' => PageView::where('created_at', '>=', $last7Days)->count(),
                'total_clicks_7d' => ClickLog::where('created_at', '>=', $last7Days)->count(),
                'unique_visitors_7d' => PageView::where('created_at', '>=', $last7Days)
                    ->distinct()
                    ->count('session_id'),
            ];

            $recent_posts = Post::with(['author', 'categories'])
                ->latest()
                ->limit(5)
                ->get();

            return view('admin.dashboard', compact(
                'stats',
                'recent_posts',
                'analytics',
                'pageViewsData',
                'affiliateClicksData',
                'topPosts',
                'topAffiliateLinks',
                'deviceStats'
            ));
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}