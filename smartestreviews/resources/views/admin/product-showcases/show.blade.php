@extends('layouts.admin')

@section('title', 'View Product Showcase')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Product Showcase Details</h2>
            <p class="mt-1 text-sm text-gray-600">View details for: {{ $productShowcase->product_name }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.product-showcases.edit', $productShowcase) }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                Edit
            </a>
            <a href="{{ route('admin.product-showcases.index') }}" 
               class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 transition-colors">
                Back to List
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Product Information -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Product Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                    <p class="text-sm text-gray-900 font-semibold">{{ $productShowcase->product_name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                    <p class="text-sm text-gray-900">{{ $productShowcase->brand ?? 'N/A' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                    <p class="text-sm text-gray-900 font-semibold text-green-600">
                        {{ $productShowcase->display_price ?: 'N/A' }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                    <div class="flex items-center space-x-2">
                        @if($productShowcase->rating)
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $productShowcase->rating)
                                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $productShowcase->rating }}/5</span>
                            @if($productShowcase->review_count > 0)
                                <span class="text-sm text-gray-500">({{ number_format($productShowcase->review_count) }} reviews)</span>
                            @endif
                        @else
                            <span class="text-sm text-gray-500">Not rated</span>
                        @endif
                    </div>
                </div>
            </div>

            @if($productShowcase->description)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <p class="text-sm text-gray-900">{{ $productShowcase->description }}</p>
                </div>
            @endif
        </div>

        <!-- Affiliate Information -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Affiliate Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Affiliate URL</label>
                    <a href="{{ $productShowcase->affiliate_url }}" 
                       target="_blank"
                       rel="noopener noreferrer"
                       class="text-sm text-blue-600 hover:text-blue-800 break-all">
                        {{ Str::limit($productShowcase->affiliate_url, 60) }}
                    </a>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Affiliate Label</label>
                    <p class="text-sm text-gray-900">{{ $productShowcase->affiliate_label ?: 'Buy Now' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Merchant</label>
                    <p class="text-sm text-gray-900">{{ $productShowcase->merchant ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Features, Pros & Cons -->
        @if(($productShowcase->features && count($productShowcase->features) > 0) || 
            ($productShowcase->pros && count($productShowcase->pros) > 0) || 
            ($productShowcase->cons && count($productShowcase->cons) > 0))
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Features & Details</h3>
                
                @if($productShowcase->features && count($productShowcase->features) > 0)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Key Features</label>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($productShowcase->features as $feature)
                                <li class="text-sm text-gray-700">{{ $feature }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($productShowcase->pros && count($productShowcase->pros) > 0)
                        <div>
                            <label class="block text-sm font-medium text-green-700 mb-2">Pros</label>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($productShowcase->pros as $pro)
                                    <li class="text-sm text-green-600">{{ $pro }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($productShowcase->cons && count($productShowcase->cons) > 0)
                        <div>
                            <label class="block text-sm font-medium text-red-700 mb-2">Cons</label>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($productShowcase->cons as $con)
                                    <li class="text-sm text-red-600">{{ $con }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Product Image -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Product Image</h3>
            @if($productShowcase->product_image_url)
                <img src="{{ $productShowcase->product_image_url }}" 
                     alt="{{ $productShowcase->product_name }}"
                     class="w-full h-auto rounded-lg">
            @else
                <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <p class="text-sm text-gray-500 text-center mt-2">No image</p>
            @endif
        </div>

        <!-- Status & Settings -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Status & Settings</h3>
            
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">Status</span>
                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $productShowcase->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $productShowcase->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">Featured</span>
                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $productShowcase->is_featured ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $productShowcase->is_featured ? 'Yes' : 'No' }}
                    </span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">Sort Order</span>
                    <span class="text-sm font-medium text-gray-900">{{ $productShowcase->sort_order ?? 0 }}</span>
                </div>
            </div>
        </div>

        <!-- Associated Post -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Associated Post</h3>
            
            @if($productShowcase->post)
                <div>
                    <a href="{{ route('posts.show', [$productShowcase->post->published_at->year, $productShowcase->post->published_at->format('m'), $productShowcase->post->slug]) }}" 
                       target="_blank"
                       class="text-sm font-medium text-blue-600 hover:text-blue-800 block mb-2">
                        {{ $productShowcase->post->title }}
                    </a>
                    <p class="text-xs text-gray-500">
                        Published: {{ $productShowcase->post->published_at->format('M j, Y') }}
                    </p>
                </div>
            @else
                <p class="text-sm text-gray-500">No post associated</p>
            @endif
        </div>

        <!-- Metadata -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Metadata</h3>
            
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Created:</span>
                    <span class="text-gray-900">{{ $productShowcase->created_at->format('M j, Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Updated:</span>
                    <span class="text-gray-900">{{ $productShowcase->updated_at->format('M j, Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

