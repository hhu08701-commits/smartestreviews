@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Main Stats Cards - Compact -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-xs text-gray-500">Posts</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['total_posts'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-xs text-gray-500">Published</p>
            <p class="text-xl font-bold text-green-600">{{ $stats['published_posts'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-xs text-gray-500">Drafts</p>
            <p class="text-xl font-bold text-yellow-600">{{ $stats['draft_posts'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-xs text-gray-500">Categories</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['total_categories'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-xs text-gray-500">Affiliate Links</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['total_affiliate_links'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-xs text-gray-500">Active Links</p>
            <p class="text-xl font-bold text-green-600">{{ $stats['active_affiliate_links'] }}</p>
        </div>
    </div>

    <!-- Analytics Cards - Compact -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-lg shadow-lg p-4 border-2 border-blue-500">
            <p class="text-xs font-semibold text-gray-700 mb-1">Views (30d)</p>
            <p class="text-3xl font-extrabold text-gray-900">{{ number_format($analytics['total_views_30d']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-4 border-2 border-green-500">
            <p class="text-xs font-semibold text-gray-700 mb-1">Clicks (30d)</p>
            <p class="text-3xl font-extrabold text-gray-900">{{ number_format($analytics['total_clicks_30d']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-4 border-2 border-purple-500">
            <p class="text-xs font-semibold text-gray-700 mb-1">Views (7d)</p>
            <p class="text-3xl font-extrabold text-gray-900">{{ number_format($analytics['total_views_7d']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-4 border-2 border-indigo-500">
            <p class="text-xs font-semibold text-gray-700 mb-1">Clicks (7d)</p>
            <p class="text-3xl font-extrabold text-gray-900">{{ number_format($analytics['total_clicks_7d']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-4 border-2 border-pink-500">
            <p class="text-xs font-semibold text-gray-700 mb-1">Visitors (7d)</p>
            <p class="text-3xl font-extrabold text-gray-900">{{ number_format($analytics['unique_visitors_7d']) }}</p>
        </div>
    </div>

    <!-- Charts Section - Compact -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-sm font-medium text-gray-900 mb-3">Lượt xem trang (30 ngày)</h3>
            <canvas id="pageViewsChart" style="max-height: 200px;"></canvas>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-sm font-medium text-gray-900 mb-3">Lượt click affiliate (30 ngày)</h3>
            <canvas id="affiliateClicksChart" style="max-height: 200px;"></canvas>
        </div>
    </div>

    <!-- Bottom Section - Compact -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Device Chart -->
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-sm font-medium text-gray-900 mb-3">Thiết bị truy cập</h3>
            <canvas id="deviceChart" style="max-height: 180px;"></canvas>
        </div>

        <!-- Top Posts -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-4 py-3 border-b border-gray-200">
                <h3 class="text-sm font-medium text-gray-900">Top bài viết</h3>
            </div>
            <div class="p-4">
                @forelse($topPosts->take(5) as $post)
                    <div class="mb-3 pb-3 border-b border-gray-100 last:border-0 last:mb-0">
                        <p class="text-xs font-medium text-gray-900 truncate">{{ $post->title }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ number_format($post->page_views_count) }} lượt xem</p>
                    </div>
                @empty
                    <p class="text-xs text-gray-500 text-center">Chưa có dữ liệu</p>
                @endforelse
            </div>
        </div>

        <!-- Top Affiliate Links -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-4 py-3 border-b border-gray-200">
                <h3 class="text-sm font-medium text-gray-900">Top affiliate links</h3>
            </div>
            <div class="p-4">
                @forelse($topAffiliateLinks->take(5) as $link)
                    <div class="mb-3 pb-3 border-b border-gray-100 last:border-0 last:mb-0">
                        <p class="text-xs font-medium text-gray-900 truncate">{{ $link->label }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ number_format($link->click_logs_count) }} lượt click</p>
                    </div>
                @empty
                    <p class="text-xs text-gray-500 text-center">Chưa có dữ liệu</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Posts - Compact -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-sm font-medium text-gray-900">Recent Posts</h3>
            <a href="{{ route('admin.posts.create') }}" class="text-xs text-indigo-600 hover:text-indigo-800">+ New Post</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recent_posts->take(5) as $post)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="text-xs font-medium text-gray-900">{{ Str::limit($post->title, 40) }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-1 rounded
                                {{ $post->post_type === 'review' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $post->post_type === 'list' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $post->post_type === 'how-to' ? 'bg-purple-100 text-purple-800' : '' }}">
                                {{ ucfirst($post->post_type) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-1 rounded
                                {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($post->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs">
                            <a href="{{ route('admin.posts.edit', $post) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-center text-xs text-gray-500">No posts found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const pageViewsData = @json($pageViewsData);
    const affiliateClicksData = @json($affiliateClicksData);
    const deviceStats = @json($deviceStats);
    
    const labels = [];
    const pageViewsValues = [];
    const affiliateClicksValues = [];
    
    for (let i = 29; i >= 0; i--) {
        const date = new Date();
        date.setDate(date.getDate() - i);
        const dateStr = date.toISOString().split('T')[0];
        labels.push(date.toLocaleDateString('vi-VN', { month: 'short', day: 'numeric' }));
        pageViewsValues.push(pageViewsData[dateStr] || 0);
        affiliateClicksValues.push(affiliateClicksData[dateStr] || 0);
    }
    
    // Page Views Chart
    new Chart(document.getElementById('pageViewsChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                data: pageViewsValues,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });
    
    // Affiliate Clicks Chart
    new Chart(document.getElementById('affiliateClicksChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                data: affiliateClicksValues,
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });
    
    // Device Chart
    const deviceLabels = Object.keys(deviceStats);
    const deviceValues = Object.values(deviceStats);
    
    new Chart(document.getElementById('deviceChart'), {
        type: 'doughnut',
        data: {
            labels: deviceLabels.map(l => l ? l.charAt(0).toUpperCase() + l.slice(1) : 'Unknown'),
            datasets: [{
                data: deviceValues,
                backgroundColor: ['rgb(59, 130, 246)', 'rgb(34, 197, 94)', 'rgb(168, 85, 247)', 'rgb(239, 68, 68)'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } } }
        }
    });
</script>
@endpush
@endsection
