@extends('layouts.admin')

@section('title', 'View Post')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $post->title }}</h2>
            <p class="mt-1 text-sm text-gray-600">Post Details</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.posts.edit', $post) }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                Edit Post
            </a>
            <a href="{{ route('admin.posts.index') }}" 
               class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors">
                Back to Posts
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Post Information -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Post Information</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $post->title }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Slug</label>
                    <p class="mt-1 text-sm text-gray-900 font-mono">{{ $post->slug }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Post Type</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $post->post_type === 'review' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $post->post_type === 'list' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $post->post_type === 'how-to' ? 'bg-purple-100 text-purple-800' : '' }}">
                        {{ ucfirst(str_replace('-', ' ', $post->post_type)) }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ ucfirst($post->status) }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Author</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $post->author->name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Published At</label>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ $post->published_at ? $post->published_at->format('M d, Y H:i') : 'Not published' }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Views</label>
                    <p class="mt-1 text-sm text-gray-900">{{ number_format($post->views_count) }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Clicks</label>
                    <p class="mt-1 text-sm text-gray-900">{{ number_format($post->clicks_count) }}</p>
                </div>
            </div>
        </div>

        <!-- Featured Image -->
        @if($post->featured_image_url)
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Featured Image</h3>
                
                <div class="space-y-4">
                    <div class="aspect-w-16 aspect-h-9">
                        <img src="{{ $post->featured_image_url }}" 
                             alt="{{ $post->featured_image_alt ?: $post->title }}"
                             class="w-full h-64 object-cover rounded-lg">
                    </div>
                    
                    @if($post->featured_image_alt)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Alt Text</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $post->featured_image_alt }}</p>
                        </div>
                    @endif
                    
                    @if($post->featured_image_caption)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Caption</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $post->featured_image_caption }}</p>
                        </div>
                    @endif
                    
                    @if($post->image_original_name)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Original Filename</label>
                            <p class="mt-1 text-sm text-gray-900 font-mono">{{ $post->image_original_name }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Content -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Content</h3>
            
            <div class="prose prose-sm max-w-none">
                {!! $post->content !!}
            </div>
        </div>

        <!-- Excerpt -->
        @if($post->excerpt)
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Excerpt</h3>
                <p class="text-sm text-gray-700">{{ $post->excerpt }}</p>
            </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Categories -->
        @if($post->categories->count() > 0)
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Categories</h3>
                <div class="space-y-2">
                    @foreach($post->categories as $category)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $category->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Tags -->
        @if($post->tags->count() > 0)
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Tags</h3>
                <div class="space-y-2">
                    @foreach($post->tags as $tag)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ $tag->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Affiliate Links -->
        @if($post->affiliateLinks->count() > 0)
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Affiliate Links</h3>
                <div class="space-y-3">
                    @foreach($post->affiliateLinks as $link)
                        <div class="border border-gray-200 rounded-lg p-3">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $link->label }}</p>
                                    @if($link->merchant)
                                        <p class="text-xs text-gray-500">{{ $link->merchant }}</p>
                                    @endif
                                </div>
                                <div class="ml-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        {{ $link->enabled ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $link->enabled ? 'Active' : 'Disabled' }}
                                    </span>
                                </div>
                            </div>
                            <div class="mt-2 text-xs text-gray-500">
                                <p>Clicks: {{ number_format($link->clicks_count) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- List Items -->
        @if($post->listItems->count() > 0)
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">List Items</h3>
                <div class="space-y-3">
                    @foreach($post->listItems as $item)
                        <div class="border border-gray-200 rounded-lg p-3">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">#{{ $item->rank }} {{ $item->product_name }}</p>
                                    @if($item->brand)
                                        <p class="text-xs text-gray-500">{{ $item->brand }}</p>
                                    @endif
                                </div>
                                @if($item->rating)
                                    <div class="ml-2">
                                        <span class="text-sm font-medium text-gray-900">{{ $item->rating }}/5</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- FAQs -->
        @if($post->faqs->count() > 0)
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">FAQs</h3>
                <div class="space-y-3">
                    @foreach($post->faqs as $faq)
                        <div class="border border-gray-200 rounded-lg p-3">
                            <p class="text-sm font-medium text-gray-900">{{ $faq->question }}</p>
                            <p class="text-xs text-gray-600 mt-1">{{ Str::limit($faq->answer, 100) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ $post->url }}" target="_blank" 
                   class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors text-center block">
                    View Live Post
                </a>
                <a href="{{ route('admin.posts.edit', $post) }}" 
                   class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors text-center block">
                    Edit Post
                </a>
                <a href="{{ route('admin.affiliate-links.create') }}?post_id={{ $post->id }}" 
                   class="w-full bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition-colors text-center block">
                    Add Affiliate Link
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
