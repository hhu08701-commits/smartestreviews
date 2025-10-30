@extends('layouts.app')

@section('title', 'Smartest Reviews - Expert Product Reviews & Comparisons')
@section('description', 'Discover the best products with our expert reviews, detailed comparisons, and unbiased recommendations. Find your perfect match today.')

@section('content')
<div x-data="app()">
    <!-- Breaking News Banner -->
    <div class="bg-red-600 text-white py-2">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <span class="bg-red-700 px-3 py-1 rounded text-sm font-bold mr-4">BREAKING NEWS</span>
                    <span class="text-sm">Latest product reviews and expert recommendations</span>
                </div>
                <div class="hidden md:flex space-x-4 text-sm">
                    <a href="#" class="hover:text-red-200 transition-colors">The 2025 List Best Chocolates you need to try at least once.</a>
                    <a href="#" class="hover:text-red-200 transition-colors">Cometeer Coffee Review</a>
                    <a href="#" class="hover:text-red-200 transition-colors">Top 5 Multivitamins 2025</a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-3">
                <!-- Slideshow Section -->
                <div class="mb-8">
                    <x-slideshow :slides="$slides" />
                </div>

                <!-- Latest Reviews -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Latest Reviews</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($latestPosts as $post)
                            <article class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-lg transition-shadow card-equal-height">
                                <!-- Article Image -->
                                @if($post->featured_image)
                                    <div class="aspect-w-16 aspect-h-9">
                                        <img src="{{ $post->featured_image }}" 
                                             alt="{{ $post->title }}"
                                             class="w-full h-48 object-cover">
                                    </div>
                                @endif
                                
                                <div class="p-6 card-content">
                                    <!-- Article Meta -->
                                    <div class="flex items-center space-x-2 mb-3">
                                        <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-xs">7 min read</span>
                                        @if($post->categories->count() > 0)
                                            <span class="bg-pink-100 text-pink-800 px-2 py-1 rounded-full text-xs font-medium">
                                                {{ $post->categories->first()->name }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Article Title -->
                                    <h3 class="text-lg font-bold text-gray-900 mb-3 line-clamp-2 leading-tight">
                                        <a href="{{ route('posts.show', [$post->published_at->year, $post->published_at->month, $post->slug]) }}" 
                                           class="hover:text-blue-600 transition-colors">
                                            {{ $post->title }}
                                        </a>
                                    </h3>
                                    
                                    <!-- Article Content Preview -->
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-3 leading-relaxed">
                                        {{ Str::limit(strip_tags($post->content), 120) }}
                                    </p>
                                    
                                    <!-- Author & Date -->
                                    <div class="flex items-center justify-between card-footer">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 bg-gray-300 rounded-full flex items-center justify-center mr-2">
                                                <i class="fas fa-user text-gray-600 text-xs"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs font-medium text-gray-900">{{ $post->author->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $post->published_at->format('M j, Y') }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('posts.show', [$post->published_at->year, $post->published_at->month, $post->slug]) }}" 
                                           class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors text-sm">
                                            Read More
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Hot Products -->
                <x-hot-products :hotProducts="$hotProducts" :showViewAllButton="$showViewAllButton" />
                
                <!-- Categories -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Categories</h3>
                    <div class="space-y-2">
                        @foreach($categories as $category)
                            <a href="{{ route('categories.show', $category->slug) }}" 
                               class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-sm font-semibold mr-3" 
                                         style="background-color: {{ $category->color }}">
                                        @if($category->icon)
                                            <i class="{{ $category->icon }}"></i>
                                        @else
                                            {{ strtoupper(substr($category->name, 0, 2)) }}
                                        @endif
                                    </div>
                                    <span class="text-gray-900 font-medium">{{ $category->name }}</span>
                                </div>
                                <span class="text-gray-500 text-sm">{{ $category->posts_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection