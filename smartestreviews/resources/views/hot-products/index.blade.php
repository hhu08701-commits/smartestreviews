@extends('layouts.app')

@section('title', 'Hot Products - Trending Now')
@section('description', 'Discover the hottest trending products with the best ratings and reviews.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">🔥 Hot Products</h1>
        <p class="text-gray-600">Trending products with the best ratings and reviews</p>
    </div>

    <!-- Hot Products Grid -->
    @if($hotProducts->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($hotProducts as $index => $product)
                @php
                    $hasUrl = $product->url && !empty($product->url);
                @endphp
                <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 hover:shadow-xl transition-shadow">
                    @if($hasUrl)
                        <a href="{{ $product->url }}" target="_blank" rel="sponsored nofollow" class="block">
                    @endif
                    
                    @if($product->image)
                        <div class="relative">
                            <img src="{{ $product->image }}" 
                                 alt="{{ $product->name }}"
                                 class="w-full h-48 object-cover">
                            <!-- Rank Badge Overlay -->
                            <div class="absolute top-2 left-2">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold text-white
                                    @if($index === 0) bg-yellow-500
                                    @elseif($index === 1) bg-gray-400
                                    @elseif($index === 2) bg-orange-600
                                    @else bg-gray-600 @endif">
                                    {{ $hotProducts->firstItem() + $index }}
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold text-gray-500 uppercase">{{ $product->brand }}</span>
                            @if($product->trending)
                                <span class="flex items-center text-xs font-medium text-red-600">
                                    <svg class="w-3 h-3 mr-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    +{{ $product->trending }}%
                                </span>
                            @endif
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                            {{ $product->name }}
                        </h3>

                        @if($product->rating)
                            <div class="flex items-center space-x-2 mb-3">
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($product->rating))
                                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-sm font-medium text-gray-700">{{ number_format($product->rating, 1) }}/5</span>
                            </div>
                        @endif

                        @if($product->price)
                            <p class="text-xl font-bold text-blue-600 mb-3">{{ $product->price }}</p>
                        @endif

                        @if($hasUrl)
                            <div class="mt-4">
                                <span class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    View Product
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                </span>
                            </div>
                        @endif
                    </div>
                    
                    @if($hasUrl)
                        </a>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $hotProducts->links() }}
        </div>
    @else
        <div class="text-center py-12 bg-white rounded-lg shadow-lg">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No hot products available</h3>
            <p class="mt-1 text-sm text-gray-500">Check back later for trending products.</p>
        </div>
    @endif
</div>
@endsection

