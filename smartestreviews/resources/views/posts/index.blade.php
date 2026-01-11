@extends('layouts.app')

@php
    $pageTitle = 'Latest Posts';
    $pageDescription = 'Browse all our latest product reviews, comparisons, and expert recommendations.';
    
    if(request()->has('year') || request()->has('month')) {
        $year = request()->get('year');
        $month = request()->get('month');
        $monthName = $month ? \Carbon\Carbon::create($year, intval($month), 1)->format('F') : null;
        $pageTitle = 'Posts from ' . ($monthName ? $monthName . ' ' . $year : $year);
        $pageDescription = 'Browse all product reviews published in ' . ($monthName ? $monthName . ' ' . $year : $year) . '.';
    }
@endphp

@section('title', $pageTitle . ' - Smartest Reviews')
@section('description', $pageDescription)

@section('content')
<div style="padding: 40px 0;">
    <div class="container-wrapper">
        <div class="af-container-row clearfix" style="display: flex; flex-wrap: wrap; margin: 0 -15px;">
            <!-- Main Content (75%) -->
            <div class="col-66 pad float-l" style="width: 75%; padding: 0 15px; float: left;">
                <!-- Archive Title -->
                @if(request()->has('year') || request()->has('month'))
                    @php
                        $year = request()->get('year');
                        $month = request()->get('month');
                        $monthName = $month ? \Carbon\Carbon::create($year, intval($month), 1)->format('F') : null;
                    @endphp
                    <div style="margin-bottom: 30px; padding-bottom: 15px; border-bottom: 2px solid #f8c2eb;">
                        <h1 style="margin: 0; font-size: 28px; font-weight: 700; font-family: 'Montserrat', sans-serif; color: #333;">
                            Posts from {{ $monthName ? $monthName . ' ' . $year : $year }}
                            @if($posts->count() > 0)
                                <span style="font-size: 18px; font-weight: 400; color: #999; margin-left: 10px;">
                                    ({{ $posts->total() }} {{ Str::plural('post', $posts->total()) }})
                                </span>
                            @endif
                        </h1>
                    </div>
                @endif
                
                <!-- Posts List -->
            @if($posts->count() > 0)
                    @foreach($posts as $post)
                    <article class="aft-main-banner-latest-posts" style="margin-bottom: 30px; background: #fff; border-radius: 0; overflow: hidden;">
                        <div class="af-double-column list-style clearfix aft-list-show-image" style="display: flex; gap: 20px; align-items: flex-start;">
                            <!-- Image Column -->
                                <div class="col-3 float-l pos-rel read-img read-bg-img post-image-col" style="width: 33.333%; flex-shrink: 0;">
                                <a href="{{ route('posts.show', [$post->published_at->year, str_pad($post->published_at->month, 2, '0', STR_PAD_LEFT), $post->slug]) }}" class="aft-post-image-link" style="display: block; position: relative;">
                                    @if($post->featured_image)
                                        <img src="{{ $post->featured_image }}" 
                                             alt="{{ $post->title }}" 
                                             style="width: 100%; height: 200px; object-fit: cover; object-position: center; display: block; border-radius: 4px;"
                                             loading="lazy"
                                             onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div style="width: 100%; height: 200px; background: #f0f0f0; border-radius: 4px; display: none; align-items: center; justify-content: center;">
                                            <i class="fas fa-image" style="font-size: 48px; color: #ccc;"></i>
                                        </div>
                                    @else
                                        <div style="width: 100%; height: 200px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-image" style="font-size: 48px; color: #ccc;"></i>
                                        </div>
                                    @endif
                                    
                                    <!-- Min read badge -->
                                    <div style="position: absolute; top: 12px; left: 12px;">
                                        <span style="background: #f8c2eb; color: #000; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: 700;">
                                            {{ ceil(str_word_count(strip_tags($post->content)) / 200) }} min read
                                        </span>
                                    </div>
                                    
                                    <!-- Category badges -->
                                    @if($post->categories->count() > 0)
                                    <div style="position: absolute; bottom: 12px; left: 12px; display: flex; flex-direction: column; gap: 5px; align-items: flex-start;">
                                        @foreach($post->categories->take(2) as $category)
                                            <span style="background: #f8c2eb; color: #000; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: 700;">
                                                {{ $category->name }}
                                        </span>
                                        @endforeach
                                    </div>
                                    @endif
                                </a>
                            </div>
                            
                            <!-- Content Column -->
                            <div class="col-66 float-l pad read-details color-tp-pad" style="width: 66.666%; flex: 1; min-width: 0;">
                                <div class="read-title" style="margin-bottom: 12px;">
                                    <h4 style="margin: 0; font-size: 20px; font-weight: 700; line-height: 1.4; font-family: 'Montserrat', sans-serif;">
                                        <a href="{{ route('posts.show', [$post->published_at->year, str_pad($post->published_at->month, 2, '0', STR_PAD_LEFT), $post->slug]) }}" 
                                           style="color: #333; text-decoration: none; transition: color 0.3s; display: block;"
                                           onmouseover="this.style.color='#f8c2eb'" onmouseout="this.style.color='#333'">
                                            {{ $post->title }}
                                        </a>
                                    </h4>
                                </div>

                                <div class="post-item-metadata entry-meta" style="margin-bottom: 15px; font-size: 13px; color: #666; text-transform: uppercase;">
                                    <span style="font-weight: 600; margin-right: 15px;">{{ strtoupper($post->author->name) }}</span>
                                    <span class="item-metadata posts-date">
                                        <i class="far fa-clock" style="margin-right: 5px;"></i>
                                        {{ $post->published_at->format('F j, Y') }}
                                    </span>
                                </div>
                                
                                <p style="margin: 0 0 15px 0; font-size: 14px; line-height: 1.6; color: #666; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ Str::limit(strip_tags($post->excerpt ?: $post->content), 200) }}
                                </p>
                                
                                <a href="{{ route('posts.show', [$post->published_at->year, str_pad($post->published_at->month, 2, '0', STR_PAD_LEFT), $post->slug]) }}" 
                                   style="display: inline-block; background: #000; color: #fff; padding: 10px 24px; border-radius: 4px; text-decoration: none; font-weight: 700; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; transition: background 0.3s;"
                                   onmouseover="this.style.background='#333'" onmouseout="this.style.background='#000'">
                                    READ MORE
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach

                <!-- Pagination -->
                    <div class="col col-ten" style="width: 100%;">
                        <div class="chromenews-pagination" style="margin: 40px 0; text-align: center;">
                            {!! $posts->appends(request()->query())->links('vendor.pagination.custom') !!}
                        </div>
                </div>
            @else
                <div style="text-align: center; padding: 60px 20px; background: #f9f9f9; border-radius: 8px;">
                    <h3 style="font-size: 20px; font-weight: 700; font-family: 'Montserrat', sans-serif; color: #333; margin-bottom: 10px;">No posts found</h3>
                    @if(request()->has('year') || request()->has('month'))
                        @php
                            $year = request()->get('year');
                            $month = request()->get('month');
                            $monthName = $month ? \Carbon\Carbon::create($year, intval($month), 1)->format('F') : null;
                        @endphp
                        <p style="color: #666; font-size: 14px; margin-bottom: 20px;">
                            No posts found for {{ $monthName ? $monthName . ' ' . $year : $year }}.
                        </p>
                    @else
                        <p style="color: #666; font-size: 14px; margin-bottom: 20px;">
                            Try adjusting your search criteria or browse all posts.
                        </p>
                    @endif
                    <a href="{{ route('posts.index') }}" 
                       style="display: inline-block; background: #f8c2eb; color: #000; padding: 10px 24px; border-radius: 4px; text-decoration: none; font-weight: 700; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; transition: background 0.3s;"
                       onmouseover="this.style.background='#e8a8d8'" onmouseout="this.style.background='#f8c2eb'">
                        VIEW ALL POSTS
                    </a>
                </div>
            @endif
        </div>

            <!-- Sidebar (25%) -->
            <aside class="col-1 pad float-l widget-area" style="width: 25%; padding: 0 15px; float: left;">
                <!-- Categories Widget -->
                <div class="widget chromenews-widget" style="margin-bottom: 30px; background: #f5f5f5; padding: 20px; border-radius: 0;">
                    <h2 class="widget-title" style="margin: 0 0 15px 0; font-size: 18px; font-weight: 700; font-family: 'Montserrat', sans-serif; color: #333; text-transform: uppercase; letter-spacing: 0.5px; padding-bottom: 8px; border-bottom: 2px solid #f8c2eb;">
                        CATEGORIES
                    </h2>
                    <ul class="wp-block-categories-list" style="list-style: none; padding: 0; margin: 0;">
                        @foreach($categories as $index => $category)
                            <li class="cat-item" style="padding: 8px 0; {{ $index < $categories->count() - 1 ? 'border-bottom: 1px solid #e0e0e0;' : '' }}">
                                <a href="{{ route('categories.show', $category->slug) }}" 
                                   style="color: #333; text-decoration: none; font-size: 14px; font-family: 'Montserrat', sans-serif; transition: color 0.3s; display: block;"
                                   onmouseover="this.style.color='#f8c2eb'" onmouseout="this.style.color='#333'">
                                    {{ $category->name }}
                                </a>
                            </li>
                    @endforeach
                    </ul>
                </div>
            </aside>
            <div style="clear: both;"></div>
        </div>
    </div>
</div>

<style>
    /* Responsive for Posts Index */
    @media (max-width: 992px) {
        .col-66[style*="width: 75%"],
        .col-1[style*="width: 25%"] {
            width: 100% !important;
            float: none !important;
        }
        
        .col-1[style*="width: 25%"] {
            margin-top: 30px;
        }
        
        .post-image-col {
            width: 100% !important;
            margin-bottom: 15px;
        }
        
        .read-details[style*="width: 66.666%"] {
            width: 100% !important;
        }
    }
    
    @media (max-width: 768px) {
        .af-container-row {
            margin: 0 -5px !important;
        }
        
        .pad[style*="padding: 0 15px"] {
            padding: 0 5px !important;
        }
        
        article.aft-main-banner-latest-posts {
            margin-bottom: 20px !important;
        }
        
        h4[style*="font-size: 20px"] {
            font-size: 18px !important;
        }
        
        .read-more-button {
            width: 100%;
            text-align: center;
        }
    }
</style>
@endsection
