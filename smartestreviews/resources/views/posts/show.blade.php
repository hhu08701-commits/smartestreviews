@extends('layouts.app')

@section('title', $post->meta_title)
@section('description', $post->meta_description)

@section('schema')
@php
$schema = [
    "@context" => "https://schema.org",
    "@type" => "Article",
    "headline" => $post->title,
    "description" => $post->excerpt,
    "author" => [
        "@type" => "Person",
        "name" => $post->author->name
    ],
    "datePublished" => $post->published_at->toISOString(),
    "dateModified" => $post->updated_at->toISOString(),
    "publisher" => [
        "@type" => "Organization",
        "name" => "Smart Reviews",
        "logo" => [
            "@type" => "ImageObject",
            "url" => asset('images/logo.png')
        ]
    ]
];

if ($post->post_type === 'review' && $post->rating) {
    $schema['review'] = [
        "@type" => "Review",
        "itemReviewed" => [
            "@type" => "Product",
            "name" => $post->product_name ?? $post->title,
            "brand" => [
                "@type" => "Brand",
                "name" => $post->brand
            ]
        ],
        "reviewRating" => [
            "@type" => "Rating",
            "ratingValue" => $post->rating,
            "bestRating" => "5"
        ],
        "author" => [
            "@type" => "Person",
            "name" => $post->author->name
        ]
    ];
}
@endphp
<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumbs -->
    <x-breadcrumbs :items="[
        ['title' => 'Home', 'url' => route('home')],
        ['title' => $post->categories->first()->name ?? 'Category', 'url' => $post->categories->first() ? route('categories.show', $post->categories->first()) : '#'],
        ['title' => $post->title]
    ]" />

    <div class="max-w-[1600px] mx-auto px-6 sm:px-8 lg:px-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 lg:gap-12 py-8">
            <!-- Main Content -->
            <div class="lg:col-span-4">
            <!-- Post Header -->
                    <article class="bg-gradient-to-br from-white via-blue-50/30 to-sky-50/20 rounded-xl shadow-lg border border-blue-100/50 p-10 lg:p-12 mb-10 backdrop-blur-sm">
                <div class="flex items-center space-x-2 mb-4">
                    @foreach($post->categories->take(2) as $category)
                        <span 
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800"
                        >
                            {{ $category->name }}
                        </span>
                    @endforeach
                </div>

                <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6 leading-tight">{{ $post->title }}</h1>
                
                <div class="flex items-center space-x-4 text-sm text-gray-500 mb-6">
                    <span>By {{ $post->author->name }}</span>
                    <span>•</span>
                    <time datetime="{{ $post->published_at->toISOString() }}">
                        {{ $post->published_at->format('F j, Y') }}
                    </time>
                    <span>•</span>
                    <div class="flex items-center space-x-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span>{{ number_format($post->views_count) }} views</span>
                    </div>
                </div>

                @if($post->featured_image)
                    <img 
                        src="{{ $post->featured_image }}" 
                        alt="{{ $post->title }}"
                        class="w-full h-auto max-h-[500px] object-cover rounded-xl mb-8 shadow-lg"
                        loading="lazy"
                    >
                @endif


                <!-- Content với styling cải thiện -->
                <div class="prose prose-xl max-w-none 
                    prose-headings:font-bold prose-headings:text-gray-900
                    prose-p:text-gray-800 prose-p:leading-relaxed prose-p:mb-7 prose-p:text-lg
                    prose-a:text-blue-600 prose-a:font-semibold prose-a:no-underline hover:prose-a:underline hover:prose-a:text-blue-700
                    prose-strong:text-gray-900 prose-strong:font-bold
                    prose-ul:space-y-3 prose-ul:mb-7
                    prose-ol:space-y-3 prose-ol:mb-7
                    prose-li:text-gray-800 prose-li:leading-relaxed prose-li:text-lg
                    prose-h2:text-3xl prose-h2:mt-10 prose-h2:mb-5 prose-h2:font-bold
                    prose-h3:text-2xl prose-h3:mt-8 prose-h3:mb-4 prose-h3:font-bold
                    prose-img:rounded-xl prose-img:shadow-xl prose-img:my-8
                    prose-blockquote:border-l-4 prose-blockquote:border-blue-500 prose-blockquote:pl-6 prose-blockquote:italic prose-blockquote:text-gray-700 prose-blockquote:text-lg prose-blockquote:bg-blue-50 prose-blockquote:py-4 prose-blockquote:my-8
                    prose-code:text-blue-700 prose-code:bg-blue-50 prose-code:px-2 prose-code:py-1 prose-code:rounded prose-code:font-mono prose-code:text-base
                    prose-pre:bg-gray-900 prose-pre:text-gray-100 prose-pre:rounded-xl prose-pre:p-6 prose-pre:my-8">
                    {!! $post->content !!}
                </div>

                <!-- Affiliate Links Section -->
                @if($post->affiliateLinks && $post->affiliateLinks->count() > 0)
                    <x-affiliate-links :affiliateLinks="$post->affiliateLinks" :post="$post" />
                @endif
            </article>

            <!-- Product Showcases - "Where to Buy" Section -->
            @php
                // Chỉ hiển thị active showcases
                $activeProductShowcases = $post->productShowcases ? $post->productShowcases->where('is_active', true) : collect([]);
            @endphp
            @if($activeProductShowcases->count() > 0)
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 rounded-xl p-8 lg:p-10 mb-10 shadow-md">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                        {{ $post->title }}
                    </h3>
                    <div class="space-y-3">
                        @foreach($activeProductShowcases as $product)
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 rounded-lg p-4 hover:shadow-sm transition-shadow">
                                <div class="flex flex-row items-start gap-4">
                                    <!-- Product Image (nếu có) - Nhỏ lại -->
                                    @if($product->product_image_url || ($product->image_url || $product->image_path))
                                        <div class="flex-shrink-0">
                                            <img 
                                                src="{{ $product->product_image_url ?: ($product->image_url ?: asset('uploads/products/' . $product->image_path)) }}" 
                                                alt="{{ $product->product_name }}"
                                                class="w-16 h-16 object-cover rounded border border-gray-200"
                                            >
                                        </div>
                                    @endif
                                    
                                    <!-- Product Info -->
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-semibold text-gray-900 mb-2 leading-tight">{{ $product->product_name }}</h4>
                                        
                                        <div class="space-y-1.5">
                                            @if($product->brand)
                                                <p class="text-xs text-gray-600">
                                                    <span class="font-medium">Brand:</span> {{ $product->brand }}
                                                </p>
                                            @endif
                                            
                                            @if($product->rating)
                                                <div class="flex items-center gap-1.5">
                                                    <div class="flex items-center">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= floor($product->rating))
                                                                <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                                </svg>
                                                            @else
                                                                <svg class="w-3 h-3 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                                </svg>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    <span class="text-xs font-medium text-gray-600">{{ number_format($product->rating, 1) }}/5</span>
                                                </div>
                                            @endif
                                            
                                            @if($product->merchant)
                                                <p class="text-xs text-gray-600">
                                                    <span class="font-medium">Merchant:</span> {{ $product->merchant }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Button - Nhỏ lại -->
                                    <div class="flex-shrink-0">
                                        @if($product->affiliate_url)
                                            <a 
                                                href="{{ $product->cloaked_url ?: $product->affiliate_url }}" 
                                                target="_blank" 
                                                rel="sponsored nofollow noopener"
                                                class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors"
                                            >
                                                {{ $product->affiliate_label ?: 'View Product' }}
                                                <svg class="ml-1.5 w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                </svg>
                                            </a>
                                        @else
                                            <div class="inline-flex items-center justify-center px-4 py-2 bg-gray-300 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed">
                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Link đang được cập nhật
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif


            <!-- List Table for List Posts -->
            @if($post->post_type === 'list')
                <x-list-table :post="$post" />
            @endif

            <!-- FAQs -->
            @if($post->faqs->count() > 0)
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 rounded-xl p-8 lg:p-10 mb-10 shadow-md">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $post->title }} - FAQs
                    </h3>
                    <div class="space-y-3" x-data="{ openFaq: null }">
                        @foreach($post->faqs as $faq)
                            <div class="bg-white border border-blue-200 rounded-lg">
                                <button 
                                    @click="openFaq = openFaq === {{ $faq->id }} ? null : {{ $faq->id }}"
                                    class="w-full px-4 py-3 text-left flex items-center justify-between hover:bg-blue-50 transition-colors"
                                >
                                    <span class="font-medium text-gray-900">{{ $faq->question }}</span>
                                    <svg 
                                        class="w-5 h-5 text-blue-600 transition-transform duration-200"
                                        :class="{ 'rotate-180': openFaq === {{ $faq->id }} }"
                                        fill="none" 
                                        stroke="currentColor" 
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div 
                                    x-show="openFaq === {{ $faq->id }}" 
                                    x-transition
                                    class="px-4 pb-4 text-gray-700"
                                >
                                    {{ $faq->answer }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Related Posts -->
            @if($relatedPosts->count() > 0)
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 rounded-xl p-8 lg:p-10 mb-10 shadow-md">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Related Posts
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($relatedPosts as $relatedPost)
                            <div class="flex space-x-3">
                                @if($relatedPost->featured_image)
                                    <img 
                                        src="{{ $relatedPost->featured_image }}" 
                                        alt="{{ $relatedPost->title }}"
                                        class="w-20 h-20 object-cover rounded-lg flex-shrink-0"
                                        loading="lazy"
                                    >
                                @endif
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-medium text-gray-900 line-clamp-2 mb-1">
                                        <a href="{{ $relatedPost->url }}" class="hover:text-blue-600 transition-colors">
                                            {{ $relatedPost->title }}
                                        </a>
                                    </h4>
                                    <p class="text-xs text-gray-500">
                                        {{ $relatedPost->published_at->format('M j, Y') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar removed - Full width content -->
    </div>
</div>
@endsection