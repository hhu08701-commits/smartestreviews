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
<div class="max-w-4xl mx-auto">
    <!-- Breadcrumbs -->
    <x-breadcrumbs :items="[
        ['title' => 'Home', 'url' => route('home')],
        ['title' => $post->categories->first()->name ?? 'Category', 'url' => $post->categories->first() ? route('categories.show', $post->categories->first()) : '#'],
        ['title' => $post->title]
    ]" />

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-3">
            <!-- Post Header -->
            <article class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 mb-8">
                <div class="flex items-center space-x-2 mb-4">
                    @foreach($post->categories->take(2) as $category)
                        <span 
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800"
                        >
                            {{ $category->name }}
                        </span>
                    @endforeach
                </div>

                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>
                
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
                        class="w-full h-64 object-cover rounded-lg mb-6"
                        loading="lazy"
                    >
                @endif

                <div class="prose prose-lg max-w-none">
                    {!! $post->content !!}
                </div>

                <!-- Affiliate Links Section -->
                @if($post->affiliateLinks && $post->affiliateLinks->count() > 0)
                    <x-affiliate-links :affiliateLinks="$post->affiliateLinks" :post="$post" />
                @endif
            </article>

            <!-- Product Showcases -->
            @if($post->productShowcases && $post->productShowcases->count() > 0)
                <x-product-showcases :productShowcases="$post->productShowcases" />
            @endif

            <!-- Review Box for Review Posts -->
            @if($post->post_type === 'review')
                <x-review-box :post="$post" />
            @endif

            <!-- List Table for List Posts -->
            @if($post->post_type === 'list')
                <x-list-table :post="$post" />
            @endif

            <!-- FAQs -->
            @if($post->faqs->count() > 0)
                <div class="bg-white rounded-lg border border-gray-200 p-6 mb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Frequently Asked Questions</h3>
                    <div class="space-y-4" x-data="{ openFaq: null }">
                        @foreach($post->faqs as $faq)
                            <div class="border border-gray-200 rounded-lg">
                                <button 
                                    @click="openFaq = openFaq === {{ $faq->id }} ? null : {{ $faq->id }}"
                                    class="w-full px-4 py-3 text-left flex items-center justify-between hover:bg-gray-50 transition-colors"
                                >
                                    <span class="font-medium text-gray-900">{{ $faq->question }}</span>
                                    <svg 
                                        class="w-5 h-5 text-gray-500 transition-transform duration-200"
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
                <div class="bg-white rounded-lg border border-gray-200 p-6 mb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Related Posts</h3>
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

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Recent Posts -->
            @if($recentCategoryPosts->count() > 0)
                <x-sidebar.recent-posts :posts="$recentCategoryPosts" />
            @endif

            <!-- Sponsored Content -->
            <x-sidebar.sponsored />
        </div>
    </div>
</div>
@endsection