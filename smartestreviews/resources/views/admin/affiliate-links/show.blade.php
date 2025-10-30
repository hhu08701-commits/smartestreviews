@extends('layouts.admin')

@section('title', 'View Affiliate Link')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Affiliate Link Details</h1>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.affiliate-links.edit', $affiliateLink) }}" class="btn-primary">Edit</a>
            <a href="{{ route('admin.affiliate-links.index') }}" class="btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Links
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Label</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $affiliateLink->label }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Slug</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $affiliateLink->slug }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">URL</dt>
                        <dd class="mt-1 text-sm text-gray-900 break-all">
                            <a href="{{ $affiliateLink->url }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                {{ $affiliateLink->url }}
                            </a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Merchant</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $affiliateLink->merchant ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Rel Attribute</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $affiliateLink->rel ?? 'None' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $affiliateLink->enabled ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $affiliateLink->enabled ? 'Enabled' : 'Disabled' }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created At</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $affiliateLink->created_at->format('M d, Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $affiliateLink->updated_at->format('M d, Y H:i') }}</dd>
                    </div>
                </dl>
            </div>

            @if($affiliateLink->utm_params)
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">UTM Parameters</h3>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <code class="text-sm text-gray-800">{{ $affiliateLink->utm_params }}</code>
                    </div>
                </div>
            @endif

            @if($affiliateLink->post)
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Associated Post</h3>
                    <div class="border rounded-lg p-4">
                        <h4 class="font-medium text-gray-900">{{ $affiliateLink->post->title }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($affiliateLink->post->excerpt, 150) }}</p>
                        <div class="mt-3 flex items-center space-x-4 text-sm text-gray-500">
                            <span>Type: {{ ucfirst($affiliateLink->post->post_type) }}</span>
                            <span>Status: {{ ucfirst($affiliateLink->post->status) }}</span>
                            @if($affiliateLink->post->published_at)
                                <span>Published: {{ $affiliateLink->post->published_at->format('M d, Y') }}</span>
                            @endif
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('posts.show', [$affiliateLink->post->published_at->year, $affiliateLink->post->published_at->format('m'), $affiliateLink->post->slug]) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm">
                                View Post →
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            @if($affiliateLink->product)
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Associated Product</h3>
                    <div class="border rounded-lg p-4">
                        <h4 class="font-medium text-gray-900">{{ $affiliateLink->product->name }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ $affiliateLink->product->brand }}</p>
                        @if($affiliateLink->product->price_text)
                            <p class="text-sm text-gray-600 mt-1">Price: {{ $affiliateLink->product->price_text }}</p>
                        @endif
                        @if($affiliateLink->product->rating)
                            <div class="mt-2 flex items-center">
                                <div class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $affiliateLink->product->rating)
                                            <svg class="star" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @else
                                            <svg class="star-empty" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endif
                                    @endfor
                                </div>
                                <span class="ml-2 text-sm text-gray-600">{{ $affiliateLink->product->rating }}/5</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Stats -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Statistics</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Total Clicks</dt>
                        <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $affiliateLink->clicks_count }}</dd>
                    </div>
                    @if($affiliateLink->last_clicked_at)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Clicked</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $affiliateLink->last_clicked_at->format('M d, Y H:i') }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <!-- Cloaked URL -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Cloaked URL</h3>
                <div class="bg-gray-50 p-3 rounded-md">
                    <code class="text-sm text-gray-800 break-all">{{ $affiliateLink->cloaked_url }}</code>
                </div>
                <p class="mt-2 text-xs text-gray-500">Use this URL in your posts instead of the direct affiliate link.</p>
            </div>

            <!-- Actions -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.affiliate-links.edit', $affiliateLink) }}" class="w-full btn-primary text-center block">
                        Edit Link
                    </a>
                    <form action="{{ route('admin.affiliate-links.destroy', $affiliateLink) }}" method="POST" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full btn-secondary text-red-600 hover:text-red-800" 
                                onclick="return confirm('Are you sure you want to delete this affiliate link?')">
                            Delete Link
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
