@extends('layouts.admin')

@section('title', 'Posts Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900" style="font-family: 'Montserrat', sans-serif;">Posts Management</h2>
            <p class="mt-1 text-sm text-gray-600">Quản lý tất cả bài viết - đăng bài ở đây sẽ hiển thị ngay trên client</p>
        </div>
        <a href="{{ route('admin.posts.create') }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-black bg-[#f8c2eb] hover:bg-[#e8a8d8] font-semibold transition-colors"
           style="font-family: 'Montserrat', sans-serif;">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create New Post
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <p class="text-xs text-gray-500 font-medium">Total Posts</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <p class="text-xs text-gray-500 font-medium">Published</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['published'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
            <p class="text-xs text-gray-500 font-medium">Draft</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['draft'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
            <p class="text-xs text-gray-500 font-medium">Featured</p>
            <p class="text-2xl font-bold text-purple-600">{{ $stats['featured'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-pink-500">
            <p class="text-xs text-gray-500 font-medium">Trending</p>
            <p class="text-2xl font-bold text-pink-600">{{ $stats['trending'] }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4" style="font-family: 'Montserrat', sans-serif;">Filters & Search</h3>
        <form method="GET" action="{{ route('admin.posts.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Title</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                           placeholder="Search by title..."
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm">
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status" 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm">
                        <option value="">All Status</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>

                <!-- Post Type Filter -->
                <div>
                    <label for="post_type" class="block text-sm font-medium text-gray-700 mb-1">Post Type</label>
                    <select name="post_type" id="post_type" 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm">
                        <option value="">All Types</option>
                        <option value="review" {{ request('post_type') == 'review' ? 'selected' : '' }}>Review</option>
                        <option value="list" {{ request('post_type') == 'list' ? 'selected' : '' }}>List</option>
                        <option value="how-to" {{ request('post_type') == 'how-to' ? 'selected' : '' }}>How-to</option>
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                    <select name="sort" id="sort" 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm">
                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                        <option value="published_at" {{ request('sort') == 'published_at' ? 'selected' : '' }}>Published Date</option>
                        <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title</option>
                        <option value="views_count" {{ request('sort') == 'views_count' ? 'selected' : '' }}>Views</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Category Filter -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category_id" id="category_id" 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tag Filter -->
                <div>
                    <label for="tag_id" class="block text-sm font-medium text-gray-700 mb-1">Tag</label>
                    <select name="tag_id" id="tag_id" 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm">
                        <option value="">All Tags</option>
                        @foreach($tags as $tag)
                            <option value="{{ $tag->id }}" {{ request('tag_id') == $tag->id ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Featured Filter -->
                <div>
                    <label for="is_featured" class="block text-sm font-medium text-gray-700 mb-1">Featured</label>
                    <select name="is_featured" id="is_featured" 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm">
                        <option value="">All</option>
                        <option value="1" {{ request('is_featured') == '1' ? 'selected' : '' }}>Featured Only</option>
                        <option value="0" {{ request('is_featured') == '0' ? 'selected' : '' }}>Not Featured</option>
                    </select>
                </div>

                <!-- Trending Filter -->
                <div>
                    <label for="is_trending" class="block text-sm font-medium text-gray-700 mb-1">Trending</label>
                    <select name="is_trending" id="is_trending" 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm">
                        <option value="">All</option>
                        <option value="1" {{ request('is_trending') == '1' ? 'selected' : '' }}>Trending Only</option>
                        <option value="0" {{ request('is_trending') == '0' ? 'selected' : '' }}>Not Trending</option>
                    </select>
                </div>
            </div>

            <!-- Hidden fields to preserve order -->
            @if(request('order'))
                <input type="hidden" name="order" value="{{ request('order') }}">
            @endif

            <!-- Action Buttons -->
            <div class="flex justify-between items-center pt-4 border-t">
                <button type="submit" 
                        class="bg-[#f8c2eb] text-black px-6 py-2 rounded-md hover:bg-[#e8a8d8] transition-colors font-semibold"
                        style="font-family: 'Montserrat', sans-serif;">
                    Apply Filters
                </button>
                @if(request()->hasAny(['search', 'status', 'post_type', 'category_id', 'tag_id', 'is_featured', 'is_trending', 'sort']))
                    <a href="{{ route('admin.posts.index') }}" 
                       class="text-gray-600 hover:text-gray-900 text-sm font-medium">
                        Clear All Filters
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Posts Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Featured</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trending</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($posts as $post)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-start space-x-3">
                                @if($post->featured_image)
                                <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" 
                                     class="w-16 h-16 object-cover rounded border border-gray-200 flex-shrink-0">
                                @endif
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900">{{ Str::limit($post->title, 60) }}</div>
                                    @if($post->excerpt)
                                    <div class="text-sm text-gray-500 mt-1">{{ Str::limit($post->excerpt, 80) }}</div>
                                    @endif
                                    @if($post->tags->count() > 0)
                                    <div class="flex flex-wrap gap-1 mt-2">
                                        @foreach($post->tags->take(3) as $tag)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                            {{ $tag->name }}
                                        </span>
                                        @endforeach
                                        @if($post->tags->count() > 3)
                                        <span class="text-xs text-gray-400">+{{ $post->tags->count() - 3 }} more</span>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $post->post_type === 'review' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $post->post_type === 'list' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $post->post_type === 'how-to' ? 'bg-purple-100 text-purple-800' : '' }}">
                                {{ ucfirst($post->post_type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($post->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($post->is_featured)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    ⭐ Featured
                                </span>
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($post->is_trending)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                    🔥 Trending
                                </span>
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $post->author->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $post->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                @if($post->status === 'published')
                                <a href="{{ route('posts.show', [$post->published_at->year, str_pad($post->published_at->month, 2, '0', STR_PAD_LEFT), $post->slug]) }}" 
                                   target="_blank"
                                   class="text-green-600 hover:text-green-900 font-semibold" 
                                   title="View on Client">
                                    <i class="fas fa-external-link-alt"></i> View
                                </a>
                                @endif
                                <a href="{{ route('admin.posts.show', $post) }}" 
                                   class="text-[#f8c2eb] hover:text-[#e8a8d8] font-semibold">Admin</a>
                                <a href="{{ route('admin.posts.edit', $post) }}" 
                                   class="text-[#f8c2eb] hover:text-[#e8a8d8] font-semibold">Edit</a>
                                <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Are you sure you want to delete this post?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            <div class="py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No posts found</h3>
                                <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or create a new post.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($posts->hasPages())
    <div class="flex justify-center">
        {{ $posts->links() }}
    </div>
    @endif
</div>
@endsection