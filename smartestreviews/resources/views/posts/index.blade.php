@extends('layouts.app')

@section('title', 'Latest Posts - Smartest Reviews')
@section('description', 'Browse all our latest product reviews, comparisons, and expert recommendations.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-3">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Latest Posts</h1>
                <p class="text-gray-600">Discover our latest product reviews, comparisons, and expert recommendations.</p>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <form method="GET" class="flex flex-col md:flex-row gap-4">
                    <!-- Search -->
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search posts..." 
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Category Filter -->
                    <div class="md:w-48">
                        <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort -->
                    <div class="md:w-48">
                        <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="published_at" {{ request('sort') == 'published_at' ? 'selected' : '' }}>Latest</option>
                            <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Most Viewed</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                            <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Alphabetical</option>
                        </select>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        Filter
                    </button>
                </form>
            </div>

            <!-- Posts Grid -->
            @if($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    @foreach($posts as $post)
                        <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                            @if($post->featured_image)
                                <img src="{{ $post->featured_image }}" 
                                     alt="{{ $post->title }}"
                                     class="w-full h-48 object-cover">
                            @endif
                            <div class="p-6">
                                <!-- Meta -->
                                <div class="flex items-center space-x-2 mb-3">
                                    @if($post->categories->count() > 0)
                                        <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded">
                                            {{ $post->categories->first()->name }}
                                        </span>
                                    @endif
                                    <span class="text-xs text-gray-500">{{ $post->published_at->format('M j, Y') }}</span>
                                </div>

                                <!-- Title -->
                                <h3 class="text-lg font-semibold text-gray-900 mb-3 line-clamp-2">
                                    <a href="{{ route('posts.show', [$post->published_at->year, $post->published_at->month, $post->slug]) }}" 
                                       class="hover:text-blue-600 transition-colors">
                                        {{ $post->title }}
                                    </a>
                                </h3>

                                <!-- Excerpt -->
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                    {{ Str::limit(strip_tags($post->content), 120) }}
                                </p>

                                <!-- Author & Stats -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-user mr-1"></i>
                                        {{ $post->author->name }}
                                        @if($post->views_count > 0)
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-eye mr-1"></i>
                                            {{ number_format($post->views_count) }} views
                                        @endif
                                    </div>
                                    <a href="{{ route('posts.show', [$post->published_at->year, $post->published_at->month, $post->slug]) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Read More →
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex justify-center">
                    {{ $posts->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No posts found</h3>
                    <p class="text-gray-500">Try adjusting your search criteria or browse all posts.</p>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Search -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">SEARCH</h3>
                <form method="GET" action="{{ route('posts.index') }}" class="flex">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search posts..." 
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <button type="submit" 
                            class="bg-pink-500 text-white px-4 py-2 rounded-r-md hover:bg-pink-600 transition-colors">
                        SEARCH
                    </button>
                </form>
            </div>

            <!-- Categories -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Categories</h3>
                <div class="space-y-2">
                    @foreach($categories as $category)
                        <a href="{{ route('posts.index', ['category' => $category->slug]) }}" 
                           class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="w-6 h-6 rounded-lg flex items-center justify-center text-white text-xs font-semibold mr-3" 
                                     style="background-color: {{ $category->color }}">
                                    @if($category->icon)
                                        <i class="{{ $category->icon }}"></i>
                                    @else
                                        {{ strtoupper(substr($category->name, 0, 2)) }}
                                    @endif
                                </div>
                                <span class="text-gray-900 font-medium">{{ $category->name }}</span>
                            </div>
                            <span class="text-gray-500 text-sm">{{ $category->posts_count ?? 0 }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Popular Posts -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Popular Posts</h3>
                <div class="space-y-4">
                    @foreach(\App\Models\Post::published()->orderBy('views_count', 'desc')->take(5)->get() as $index => $post)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                <span class="text-sm font-semibold text-gray-600">{{ $index + 1 }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-gray-900 line-clamp-2 mb-1">
                                    <a href="{{ route('posts.show', [$post->published_at->year, $post->published_at->month, $post->slug]) }}" 
                                       class="hover:text-blue-600 transition-colors">
                                        {{ $post->title }}
                                    </a>
                                </h4>
                                <p class="text-xs text-gray-500">{{ $post->published_at->format('M j, Y') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
