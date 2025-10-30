@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>
    
    @if($post->featured_image_url)
        <img src="{{ $post->featured_image_url }}" alt="{{ $post->featured_image_alt ?: $post->title }}" class="w-full h-64 object-cover rounded-lg mb-6">
    @endif
    
    <div class="prose prose-lg max-w-none">
        {!! $post->content !!}
    </div>

    <!-- Product Showcases -->
    @if($post->productShowcases && $post->productShowcases->count() > 0)
        <x-product-showcases :productShowcases="$post->productShowcases" />
    @endif

    <!-- Affiliate Links Section -->
    @if($post->affiliateLinks && $post->affiliateLinks->count() > 0)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 my-8">
            <h3 class="text-lg font-semibold text-blue-900 mb-4">Where to Buy</h3>
            
            <div class="space-y-4">
                @foreach($post->affiliateLinks as $link)
                    <div class="bg-white rounded-lg p-4 border border-blue-100">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $link->label }}</h4>
                                @if($link->merchant)
                                    <p class="text-sm text-gray-600 mt-1">{{ $link->merchant }}</p>
                                @endif
                            </div>
                            <div class="ml-4">
                                <a href="{{ route('affiliate.redirect', $link->slug) }}" 
                                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 inline-flex items-center"
                                   @if($link->rel) rel="{{ $link->rel }}" @endif
                                   target="_blank">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    Buy Now
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-4 text-xs text-blue-700">
                <p><strong>Affiliate Disclosure:</strong> We may earn a commission when you purchase through our links.</p>
            </div>
        </div>
    @endif
</div>
@endsection
