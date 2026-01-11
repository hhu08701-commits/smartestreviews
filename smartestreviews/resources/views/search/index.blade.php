@extends('layouts.app')

@section('title', 'Search Results' . ($query ? ' for "' . $query . '"' : '') . ' - Smart Reviews')
@section('description', 'Search results for ' . ($query ?: 'products and reviews'))

@section('content')
<div class="max-w-[1600px] mx-auto px-6 sm:px-8 lg:px-12">
    <!-- Breadcrumbs -->
    <x-breadcrumbs :items="[
        ['title' => 'Home', 'url' => route('home')],
        ['title' => 'Search Results']
    ]" />

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-3">
            <!-- Search Header -->
            <div class="bg-gradient-to-br from-white via-blue-50/30 to-sky-50/20 rounded-xl shadow-lg border border-blue-100/50 p-8 mb-8 backdrop-blur-sm">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">
                    @if($query)
                        Search Results for "{{ $query }}"
                    @else
                        Search Results
                    @endif
                </h1>
                
                <div class="flex items-center space-x-4 text-sm text-gray-500">
                    <span>{{ $posts->total() }} results found</span>
                    @if($category)
                        <span>•</span>
                        <span>Filtered by: {{ $categories->firstWhere('slug', $category)->name ?? $category }}</span>
                    @endif
                </div>
            </div>

            <!-- Search Filters -->
            <div class="bg-gradient-to-br from-white via-blue-50/30 to-sky-50/20 rounded-lg border border-blue-100/50 p-6 mb-8 backdrop-blur-sm">
                <form method="GET" action="{{ route('search') }}" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <input 
                            type="text" 
                            name="q" 
                            value="{{ $query }}"
                            placeholder="Search products, reviews..." 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>
                    
                    <div class="sm:w-48">
                        <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->slug }}" {{ $category === $cat->slug ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="sm:w-32">
                        <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Latest</option>
                            <option value="popular" {{ $sort === 'popular' ? 'selected' : '' }}>Popular</option>
                            <option value="rating" {{ $sort === 'rating' ? 'selected' : '' }}>Top Rated</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn-primary">
                        Search
                    </button>
                </form>
            </div>

            <!-- Search Results -->
            @if($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                    @foreach($posts as $post)
                        <x-post-card :post="$post" />
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="flex justify-center">
                    {{ $posts->appends(request()->query())->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg border border-gray-200 p-12 text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No results found</h3>
                    <p class="text-gray-500 mb-4">
                        @if($query)
                            No posts match your search for "{{ $query }}".
                        @else
                            Try searching for products or reviews.
                        @endif
                    </p>
                    <a href="{{ route('home') }}" class="btn-primary">
                        Browse All Posts
                    </a>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Categories -->
            <x-sidebar.categories :categories="$categories" />

            <!-- Sponsored Content -->
            <x-sidebar.sponsored />
        </div>
    </div>
</div>
@endsection
