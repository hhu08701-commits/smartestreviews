@extends('layouts.admin')

@section('title', 'Affiliate Links Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900" style="font-family: 'Montserrat', sans-serif;">Affiliate Links Management</h2>
            <p class="mt-1 text-sm text-gray-600">Quản lý các affiliate links cho bài viết</p>
        </div>
        <a href="{{ route('admin.affiliate-links.create') }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-black bg-[#f8c2eb] hover:bg-[#e8a8d8] font-semibold transition-colors"
           style="font-family: 'Montserrat', sans-serif;">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Create New Link
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <p class="text-xs text-gray-500 font-medium">Total Links</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <p class="text-xs text-gray-500 font-medium">Enabled</p>
            <p class="text-2xl font-bold text-green-600">{{ number_format($stats['enabled']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
            <p class="text-xs text-gray-500 font-medium">Disabled</p>
            <p class="text-2xl font-bold text-red-600">{{ number_format($stats['disabled']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
            <p class="text-xs text-gray-500 font-medium">Total Clicks</p>
            <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['total_clicks']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-indigo-500">
            <p class="text-xs text-gray-500 font-medium">With Posts</p>
            <p class="text-2xl font-bold text-indigo-600">{{ number_format($stats['with_posts']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
            <p class="text-xs text-gray-500 font-medium">Without Posts</p>
            <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['without_posts']) }}</p>
        </div>
    </div>

    <!-- Affiliate Links Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        @if($affiliateLinks->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Label</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">URL</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Merchant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clicks</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Post</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($affiliateLinks as $link)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $link->label }}</div>
                                    <div class="text-sm text-gray-500">Slug: {{ $link->slug }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $link->url }}">{{ $link->url }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $link->merchant ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $link->enabled ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $link->enabled ? 'Enabled' : 'Disabled' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                    {{ number_format($link->clicks_count) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($link->post)
                                        <a href="{{ route('posts.show', [$link->post->published_at->year, str_pad($link->post->published_at->month, 2, '0', STR_PAD_LEFT), $link->post->slug]) }}" 
                                           target="_blank"
                                           class="text-blue-600 hover:text-blue-800 font-medium">
                                            {{ Str::limit($link->post->title, 30) }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.affiliate-links.show', $link) }}" 
                                           class="text-green-600 hover:text-green-900 font-semibold">View</a>
                                        <a href="{{ route('admin.affiliate-links.edit', $link) }}" 
                                           class="text-[#f8c2eb] hover:text-[#e8a8d8] font-semibold">Edit</a>
                                        <form action="{{ route('admin.affiliate-links.destroy', $link) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-semibold" 
                                                    onclick="return confirm('Are you sure you want to delete this affiliate link?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($affiliateLinks->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $affiliateLinks->links() }}
            </div>
            @endif
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No affiliate links</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new affiliate link.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.affiliate-links.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-black bg-[#f8c2eb] hover:bg-[#e8a8d8] font-semibold"
                       style="font-family: 'Montserrat', sans-serif;">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create New Link
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
