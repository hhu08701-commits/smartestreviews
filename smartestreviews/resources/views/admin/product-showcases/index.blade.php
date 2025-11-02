@extends('layouts.admin')

@section('title', 'Product Showcases Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900" style="font-family: 'Montserrat', sans-serif;">Product Showcases</h2>
            <p class="mt-1 text-sm text-gray-600">Quản lý các sản phẩm được showcase trong bài viết</p>
        </div>
        <a href="{{ route('admin.product-showcases.create') }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-black bg-[#f8c2eb] hover:bg-[#e8a8d8] font-semibold transition-colors"
           style="font-family: 'Montserrat', sans-serif;">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Product Showcase
        </a>
    </div>

    <!-- Stats Cards -->
    @php
        $totalShowcases = \App\Models\ProductShowcase::count();
        $activeShowcases = \App\Models\ProductShowcase::where('is_active', true)->count();
        $featuredShowcases = \App\Models\ProductShowcase::where('is_featured', true)->count();
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <p class="text-xs text-gray-500 font-medium">Total Showcases</p>
            <p class="text-2xl font-bold text-gray-900">{{ $totalShowcases }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <p class="text-xs text-gray-500 font-medium">Active</p>
            <p class="text-2xl font-bold text-green-600">{{ $activeShowcases }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
            <p class="text-xs text-gray-500 font-medium">Featured</p>
            <p class="text-2xl font-bold text-purple-600">{{ $featuredShowcases }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4" style="font-family: 'Montserrat', sans-serif;">Filters</h3>
        <form method="GET" action="{{ route('admin.product-showcases.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Filter by Post -->
                <div>
                    <label for="post_id" class="block text-sm font-medium text-gray-700 mb-1">Filter by Post</label>
                    <select name="post_id" id="post_id" 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm"
                            onchange="this.form.submit()">
                        <option value="">All Posts</option>
                        @foreach($posts as $post)
                            <option value="{{ $post->id }}" {{ request('post_id') == $post->id ? 'selected' : '' }}>
                                {{ Str::limit($post->title, 60) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if(request('post_id'))
                <div class="flex justify-between items-center pt-4 border-t">
                    <button type="submit" 
                            class="bg-[#f8c2eb] text-black px-6 py-2 rounded-md hover:bg-[#e8a8d8] transition-colors font-semibold"
                            style="font-family: 'Montserrat', sans-serif;">
                        Apply Filters
                    </button>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('admin.product-showcases.index') }}" 
                           class="text-gray-600 hover:text-gray-900 text-sm font-medium">
                            Clear Filter
                        </a>
                        <a href="{{ route('admin.product-showcases.create', ['post_id' => request('post_id')]) }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-black bg-green-600 hover:bg-green-700 text-white font-semibold"
                           style="font-family: 'Montserrat', sans-serif;">
                            ➕ Add Product to This Post
                        </a>
                    </div>
                </div>
            @else
                <div class="flex justify-end pt-4 border-t">
                    <button type="submit" 
                            class="bg-[#f8c2eb] text-black px-6 py-2 rounded-md hover:bg-[#e8a8d8] transition-colors font-semibold"
                            style="font-family: 'Montserrat', sans-serif;">
                        Apply Filters
                    </button>
                </div>
            @endif
        </form>
    </div>

    <!-- Product Showcases Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        @if($productShowcases->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Post</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($productShowcases as $showcase)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        @if($showcase->product_image_url)
                                            <img class="h-16 w-16 rounded-lg object-cover border border-gray-200 flex-shrink-0" src="{{ $showcase->product_image_url }}" alt="{{ $showcase->product_name }}">
                                        @else
                                            <div class="h-16 w-16 rounded-lg bg-gray-200 flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-image text-gray-400 text-xl"></i>
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-medium text-gray-900">{{ $showcase->product_name }}</div>
                                            @if($showcase->brand)
                                                <div class="text-sm text-gray-500">{{ $showcase->brand }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ Str::limit($showcase->post->title, 50) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $showcase->display_price ?? '—' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($showcase->rating)
                                        <div class="flex items-center space-x-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $showcase->rating)
                                                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                                                @else
                                                    <i class="far fa-star text-gray-300 text-xs"></i>
                                                @endif
                                            @endfor
                                            <span class="text-sm text-gray-600 ml-1">{{ number_format($showcase->rating, 1) }}</span>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        @if($showcase->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Inactive
                                            </span>
                                        @endif
                                        @if($showcase->is_featured)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                ⭐ Featured
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.product-showcases.show', $showcase) }}" 
                                           class="text-green-600 hover:text-green-900 font-semibold">View</a>
                                        <a href="{{ route('admin.product-showcases.edit', $showcase) }}" 
                                           class="text-[#f8c2eb] hover:text-[#e8a8d8] font-semibold">Edit</a>
                                        <form action="{{ route('admin.product-showcases.destroy', $showcase) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Are you sure you want to delete this product showcase?')">
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
            @if($productShowcases->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $productShowcases->links() }}
            </div>
            @endif
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No product showcases</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new product showcase.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.product-showcases.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-black bg-[#f8c2eb] hover:bg-[#e8a8d8] font-semibold"
                       style="font-family: 'Montserrat', sans-serif;">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Product Showcase
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
