@extends('layouts.app')

@section('title', $category->name . ' - Smart Reviews')
@section('description', $category->description ?? 'Browse ' . $category->name . ' reviews and recommendations')

@section('content')
<div class="max-w-[1600px] mx-auto px-6 sm:px-8 lg:px-12">
    <!-- Breadcrumbs -->
    <x-breadcrumbs :items="[
        ['title' => 'Home', 'url' => route('home')],
        ['title' => $category->name]
    ]" />

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-3">
            <!-- Category Header -->
            <div class="bg-gradient-to-br from-white via-blue-50/30 to-sky-50/20 rounded-xl shadow-lg border border-blue-100/50 p-8 mb-8 backdrop-blur-sm">
                <div class="flex items-center space-x-3 mb-4">
                    <div 
                        class="w-12 h-12 rounded-lg flex items-center justify-center bg-blue-100"
                    >
                        <div 
                            class="w-6 h-6 rounded-full bg-blue-500"
                        ></div>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
                        @if($category->description)
                            <p class="text-gray-600 mt-2">{{ $category->description }}</p>
                        @endif
                    </div>
                </div>
                
                <div class="flex items-center space-x-4 text-sm text-gray-500">
                    <span>{{ $posts->total() }} posts</span>
                </div>
            </div>

            <!-- Posts Grid -->
            @if($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                    @foreach($posts as $post)
                        <x-post-card :post="$post" />
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="flex justify-center">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg border border-gray-200 p-12 text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No posts yet</h3>
                    <p class="text-gray-500">Check back soon for {{ strtolower($category->name) }} reviews and recommendations.</p>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Related Categories -->
            @if($relatedCategories->count() > 0)
                <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Related Categories</h3>
                    <div class="space-y-2">
                        @foreach($relatedCategories as $relatedCategory)
                            <a 
                                href="{{ route('categories.show', $relatedCategory) }}" 
                                class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition-colors group"
                                style="transition: all 0.3s;"
                                onmouseover="this.style.backgroundColor='#f9f0f5'; this.querySelector('span').style.color='#f8c2eb';" 
                                onmouseout="this.style.backgroundColor=''; this.querySelector('span').style.color='';"
                            >
                                <div class="flex items-center space-x-3">
                                    <div 
                                        class="w-3 h-3 rounded-full bg-blue-500"
                                    ></div>
                                    <span class="text-sm text-gray-700" style="transition: color 0.3s;">
                                        {{ $relatedCategory->name }}
                                    </span>
                                </div>
                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                    {{ $relatedCategory->posts_count }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Sponsored Content -->
            <x-sidebar.sponsored />
        </div>
    </div>
</div>
@endsection
