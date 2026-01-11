@extends('layouts.app')

@section('title', 'Smartest Reviews - Expert Product Reviews & Comparisons')
@section('description', 'Discover the best products with our expert reviews, detailed comparisons, and unbiased recommendations. Find your perfect match today.')

@section('content')
<div>
    <!-- Breaking News Marquee Section - Only show if there are Breaking News items -->
    @php
        // Check if we have actual Breaking News items (not fallback posts)
        $hasBreakingNews = $breakingNewsItems->isNotEmpty() && $breakingNewsItems->first() instanceof \App\Models\BreakingNews;
    @endphp
    
    @if($hasBreakingNews)
    <section class="aft-blocks aft-main-banner-section" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); padding: 14px 0; border-bottom: 3px solid #0ea5e9; box-shadow: 0 2px 8px rgba(14, 165, 233, 0.15);">
        <div class="banner-exclusive-posts-wrapper">
            <div class="container-wrapper">
                <div class="exclusive-posts" style="display: flex; align-items: center; overflow: hidden;">
                    <div class="exclusive-now" style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); padding: 12px 28px; margin-right: 0; border-radius: 0 8px 8px 0; position: relative; white-space: nowrap; box-shadow: 2px 0 8px rgba(14, 165, 233, 0.3);">
                        <span style="color: #fff; font-weight: 800; font-size: 14px; text-transform: uppercase; font-family: 'Montserrat', sans-serif; position: relative; z-index: 1; letter-spacing: 1px; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">Breaking News</span>
                    </div>
                    <div class="exclusive-slides" style="flex: 1; overflow: hidden; background: transparent; padding-left: 24px;">
                        <div class="marquee" style="display: flex; white-space: nowrap; animation: scroll-left 40s linear infinite;">
                            @php
                                // Helper function to render breaking news item
                                $renderItem = function($item) {
                                    $isBreakingNews = $item instanceof \App\Models\BreakingNews;
                                    $title = $isBreakingNews ? $item->title : $item->title;
                                    $imageUrl = $isBreakingNews ? $item->image_url : $item->featured_image;
                                    
                                    if ($isBreakingNews) {
                                        $url = $item->url;
                                        if (!$imageUrl && $item->post && $item->post->featured_image) {
                                            $imageUrl = $item->post->featured_image;
                                        }
                                    } else {
                                        $url = route('posts.show', [
                                            $item->published_at->year, 
                                            str_pad($item->published_at->month, 2, '0', STR_PAD_LEFT), 
                                            $item->slug
                                        ]);
                                    }
                                    
                                    // Handle image URL (absolute vs relative)
                                    if ($imageUrl && !filter_var($imageUrl, FILTER_VALIDATE_URL) && !str_starts_with($imageUrl, '/')) {
                                        $imageUrl = asset($imageUrl);
                                    } elseif ($imageUrl && !filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                                        $imageUrl = asset($imageUrl);
                                    }
                                    
                                    return compact('url', 'title', 'imageUrl');
                                };
                            @endphp
                            
                            {{-- Render tất cả items một lần duy nhất --}}
                            @foreach($breakingNewsItems as $item)
                                @php $itemData = $renderItem($item); @endphp
                                <a href="{{ $itemData['url'] }}" 
                                   style="display: inline-flex; align-items: center; margin-right: 50px; text-decoration: none; color: #333; white-space: nowrap;">
                                    @if($itemData['imageUrl'])
                                        <span class="circle-marq" style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden; margin-right: 12px; flex-shrink: 0; border: 2px solid #e0e0e0;">
                                            <img src="{{ $itemData['imageUrl'] }}" alt="{{ $itemData['title'] }}" 
                                                 style="width: 100%; height: 100%; object-fit: cover;"
                                                 loading="lazy"
                                                 onerror="this.onerror=null; this.style.display='none';">
                                        </span>
                                    @endif
                                    <span style="font-size: 14px; font-weight: 500; font-family: 'Montserrat', sans-serif; color: #333;">
                                        {{ Str::limit($itemData['title'], 65) }}
                                    </span>
                                </a>
                            @endforeach
                            
                            {{-- Duplicate toàn bộ danh sách items một lần để tạo vòng lặp mượt cho marquee (chỉ khi có items) --}}
                            @if($breakingNewsItems->count() > 0)
                                @foreach($breakingNewsItems as $item)
                                    @php $itemData = $renderItem($item); @endphp
                                    <a href="{{ $itemData['url'] }}" 
                                       style="display: inline-flex; align-items: center; margin-right: 50px; text-decoration: none; color: #333; white-space: nowrap;">
                                        @if($itemData['imageUrl'])
                                            <span class="circle-marq" style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden; margin-right: 12px; flex-shrink: 0; border: 2px solid #e0e0e0;">
                                                <img src="{{ $itemData['imageUrl'] }}" alt="{{ $itemData['title'] }}" 
                                                     style="width: 100%; height: 100%; object-fit: cover;"
                                                     loading="lazy"
                                                     onerror="this.onerror=null; this.style.display='none';">
                                            </span>
                                        @endif
                                        <span style="font-size: 14px; font-weight: 500; font-family: 'Montserrat', sans-serif; color: #333;">
                                            {{ Str::limit($itemData['title'], 65) }}
                                        </span>
                                    </a>
                                @endforeach
                            @endif
                        </div>
                </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Main Content Section -->
    <section class="aft-blocks aft-main-banner-section" style="padding: 30px 0; background: transparent;">
        <div class="container-wrapper">
            <div class="af-container-row clearfix" style="display: flex; flex-wrap: wrap; margin: 0 -15px; align-items: flex-start;">
                <!-- Left Column: Featured Banner (75%) -->
                <div class="aft-main-content" style="width: 75%; padding: 0 15px; float: left;">
                    <!-- Featured Banner Section (Slideshow) -->
                    @if($slides->count() > 0)
                    <div class="aft-main-banner-part" style="margin-bottom: 0;">
                        <div class="af-widget-carousel featured-slideshow" style="position: relative; border-radius: 8px; overflow: hidden; height: 450px;" x-data="featuredSlideshow({{ $slides->count() }})" x-init="init()">
                            @foreach($slides as $index => $slide)
                            <div x-show="currentSlide === {{ $index }}" 
                                 x-transition:enter="transition ease-out duration-500"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition ease-in duration-300"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 style="position: absolute; inset: 0; width: 100%; height: 100%;"
                                 class="featured-slide-item">
                                @if($slide->url)
                                <a href="{{ $slide->url }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   style="display: block; position: relative; width: 100%; height: 100%; cursor: pointer; text-decoration: none; transition: transform 0.3s ease;"
                                   onmouseover="this.style.transform='scale(1.02)'"
                                   onmouseout="this.style.transform='scale(1)'"
                                   class="featured-slide-link">
                                @else
                                <div style="position: relative; width: 100%; height: 100%;">
                                @endif
                                    @if($slide->image)
                                        <img src="{{ $slide->image }}" 
                                             alt="{{ $slide->title }}" 
                                             style="width: 100%; height: 100%; object-fit: cover; object-position: center; display: block; pointer-events: none; transition: transform 0.3s ease;"
                                             class="featured-slide-image"
                                             loading="lazy"
                                             onmouseover="this.style.transform='scale(1.05)'"
                                             onmouseout="this.style.transform='scale(1)'"
                                             onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: none; align-items: center; justify-content: center; pointer-events: none;">
                                            <i class="fas fa-image" style="font-size: 72px; color: rgba(255,255,255,0.3);"></i>
                                        </div>
                                    @else
                                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; pointer-events: none;">
                                            <i class="fas fa-image" style="font-size: 72px; color: rgba(255,255,255,0.3);"></i>
                                        </div>
                                    @endif
                                
                                    <!-- Brand Name (JANINALCHAIR) -->
                                    <div style="position: absolute; top: 20px; right: 20px; z-index: 5; pointer-events: none;">
                                        <span style="background: rgba(255,255,255,0.95); color: #333; padding: 8px 16px; border-radius: 4px; font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-family: 'Montserrat', sans-serif; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                            JANINALCHAIR
                                        </span>
    </div>

                                    <!-- "X min read" Badge -->
                                    <div class="post-format-and-min-read-wrap" style="position: absolute; top: 20px; left: 20px; z-index: 5; pointer-events: none;">
                                        <span class="min-read" style="background: rgba(100,100,100,0.85); color: #fff; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; font-family: 'Montserrat', sans-serif; display: inline-block;">
                                            {{ $slide->description ? ceil(str_word_count($slide->description) / 200) : 7 }} min read
                                        </span>
                </div>

                                    <!-- Overlay Content -->
                                    <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.5) 60%, transparent 100%); padding: 35px 40px; color: #fff; pointer-events: none;">
                                        @if($slide->category)
                                        <div class="read-categories" style="margin-bottom: 15px;">
                                            <span style="background: #f8c2eb; color: #000; padding: 8px 16px; border-radius: 4px; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block; font-family: 'Montserrat', sans-serif;">
                                                {{ $slide->category }}
                                            </span>
                                        </div>
                                        @endif
                                        
                                        <h2 style="margin: 0 0 18px 0; font-size: 42px; font-weight: 700; line-height: 1.25; font-family: 'Montserrat', sans-serif; text-shadow: 0 2px 10px rgba(0,0,0,0.8); color: #fff; text-decoration: underline; text-decoration-color: transparent; transition: text-decoration-color 0.3s ease;"
                                            class="featured-slide-title">
                                            {{ $slide->title }}
                                        </h2>
                                        
                                        <div class="post-item-metadata" style="font-size: 14px; color: rgba(255,255,255,0.95); display: flex; align-items: center; gap: 15px; font-family: 'Montserrat', sans-serif;">
                                            <span style="text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">
                                                JANINALCHAIR
                                            </span>
                                            <span style="text-transform: uppercase; letter-spacing: 0.5px;">
                                                {{ now()->format('F j, Y') }}
                                            </span>
                                        </div>
                                    </div>
                                @if($slide->url)
                                </a>
                                @else
                                </div>
                                @endif
                            </div>
                            @endforeach
                            
                            <!-- Navigation Arrows -->
                            <button @click="previousSlide()" 
                                    style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.95); border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s; z-index: 10; box-shadow: 0 2px 8px rgba(0,0,0,0.3);" 
                                    onmouseover="this.style.background='#fff'; this.style.transform='translateY(-50%) scale(1.1)'" 
                                    onmouseout="this.style.background='rgba(255,255,255,0.95)'; this.style.transform='translateY(-50%) scale(1)'">
                                <i class="fas fa-chevron-left" style="color: #333; font-size: 14px;"></i>
                            </button>
                            <button @click="nextSlide()" 
                                    style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.95); border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s; z-index: 10; box-shadow: 0 2px 8px rgba(0,0,0,0.3);" 
                                    onmouseover="this.style.background='#fff'; this.style.transform='translateY(-50%) scale(1.1)'" 
                                    onmouseout="this.style.background='rgba(255,255,255,0.95)'; this.style.transform='translateY(-50%) scale(1)'">
                                <i class="fas fa-chevron-right" style="color: #333; font-size: 14px;"></i>
                            </button>

                            <!-- Slide Indicators -->
                            <div style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); display: flex; gap: 8px; z-index: 10;">
                                @foreach($slides as $index => $slide)
                                <button @click="goToSlide({{ $index }})"
                                        :style="currentSlide === {{ $index }} ? 'background: #fff; width: 30px;' : 'background: rgba(255,255,255,0.5); width: 8px;'"
                                        style="height: 8px; border-radius: 4px; border: none; cursor: pointer; transition: all 0.3s; padding: 0;">
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @elseif($latestPosts->count() > 0)
                    @php $featuredPost = $latestPosts->first(); @endphp
                    <div class="aft-main-banner-part" style="margin-bottom: 0;">
                        <div class="af-widget-carousel" style="position: relative; border-radius: 8px; overflow: hidden;">
                            <div class="featured-banner-item" style="position: relative; overflow: hidden; background: linear-gradient(135deg, #ffffff 0%, #f0f9ff 100%); border-radius: 12px; box-shadow: 0 8px 24px rgba(14, 165, 233, 0.15);">
                                <a href="{{ route('posts.show', [$featuredPost->published_at->year, str_pad($featuredPost->published_at->month, 2, '0', STR_PAD_LEFT), $featuredPost->slug]) }}" style="display: block; position: relative;">
                                    @if($featuredPost->featured_image)
                                        <img src="{{ $featuredPost->featured_image }}" 
                                             alt="{{ $featuredPost->title }}" 
                                             style="width: 100%; height: 450px; object-fit: cover; object-position: center; display: block;"
                                             loading="lazy"
                                             onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div style="width: 100%; height: 450px; background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 50%, #8b5cf6 100%); display: none; align-items: center; justify-content: center;">
                                            <i class="fas fa-image" style="font-size: 72px; color: rgba(255,255,255,0.3);"></i>
                                        </div>
                                    @else
                                        <div style="width: 100%; height: 450px; background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 50%, #8b5cf6 100%); display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-image" style="font-size: 72px; color: rgba(255,255,255,0.3);"></i>
                                    </div>
                                @endif
                                
                                    <!-- Overlay Content -->
                                    <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0,0,0,0.75) 0%, rgba(0,0,0,0.4) 50%, transparent 100%); padding: 35px 40px; color: #fff;">
                                        <div class="post-format-and-min-read-wrap" style="position: absolute; top: 20px; left: 20px;">
                                            <span class="min-read" style="background: rgba(100,100,100,0.85); color: #fff; padding: 6px 12px; border-radius: 4px; font-size: 12px; font-weight: 600;">
                                                {{ ceil(str_word_count(strip_tags($featuredPost->content)) / 200) }} min read
                                            </span>
                                        </div>

                                        @if($featuredPost->categories->count() > 0)
                                        <div class="read-categories" style="margin-bottom: 15px;">
                                            @foreach($featuredPost->categories->take(1) as $category)
                                                <span style="background: linear-gradient(135deg, #0ea5e9 0%, #8b5cf6 100%); color: #fff; padding: 6px 14px; border-radius: 6px; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block; box-shadow: 0 2px 8px rgba(14, 165, 233, 0.3);">
                                                    {{ $category->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                        @endif
                                        
                                        <h2 style="margin: 0 0 18px 0; font-size: 38px; font-weight: 700; line-height: 1.25; font-family: 'Montserrat', sans-serif; text-shadow: 0 2px 10px rgba(0,0,0,0.6);">
                                            {{ $featuredPost->title }}
                                        </h2>
                                        
                                        <div class="post-item-metadata" style="font-size: 14px; color: rgba(255,255,255,0.95); display: flex; align-items: center; gap: 15px;">
                                            <span style="text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">
                                                {{ strtoupper($featuredPost->author->name) }}
                                            </span>
                                            <span style="text-transform: uppercase; letter-spacing: 0.5px;">
                                                {{ $featuredPost->published_at->format('F j, Y') }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                </div>

                <!-- Right Column: Trending Now (25%) -->
                <div class="aft-trending-part" style="width: 25%; padding: 0 15px; float: left; align-self: flex-start; margin-top: 0; height: 450px; display: flex; flex-direction: column;">
                    <div class="af-title-subtitle-wrap" style="margin-bottom: 10px; display: flex; align-items: center; justify-content: space-between; border-bottom: 3px solid #0ea5e9; padding-bottom: 5px; flex-shrink: 0;">
                        <h4 class="widget-title" style="margin: 0; font-size: 16px; font-weight: 700; font-family: 'Montserrat', sans-serif; background: linear-gradient(135deg, #0ea5e9 0%, #8b5cf6 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; text-transform: uppercase; letter-spacing: 0.5px; line-height: 1.2; flex: 1;">
                            TRENDING NOW
                        </h4>
                        <div style="display: flex; flex-direction: row; gap: 2px; align-items: center; margin-left: 15px; flex-shrink: 0;">
                            <button class="trending-prev" style="background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); border: 1px solid rgba(14, 165, 233, 0.3); width: 24px; height: 24px; border-radius: 4px 0 0 4px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s; padding: 0; margin: 0;"
                                    onmouseover="this.style.background='linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%)'; this.querySelector('i').style.color='#fff'"
                                    onmouseout="this.style.background='linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%)'; this.querySelector('i').style.color='#0ea5e9'">
                                <i class="fas fa-chevron-up" style="font-size: 8px; color: #0ea5e9; transition: color 0.3s;"></i>
                            </button>
                            <button class="trending-next" style="background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); border: 1px solid rgba(14, 165, 233, 0.3); border-left: none; width: 24px; height: 24px; border-radius: 0 4px 4px 0; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s; padding: 0; margin: 0;"
                                    onmouseover="this.style.background='linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%)'; this.querySelector('i').style.color='#fff'"
                                    onmouseout="this.style.background='linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%)'; this.querySelector('i').style.color='#0ea5e9'">
                                <i class="fas fa-chevron-down" style="font-size: 8px; color: #0ea5e9; transition: color 0.3s;"></i>
                            </button>
                                            </div>
                                        </div>
                    
                    <div class="trending-carousel" style="position: relative; margin-top: 0; flex: 1; display: flex; flex-direction: column; overflow: hidden;">
                        <div class="trending-list" style="overflow: hidden; flex: 1; display: flex; flex-direction: column; justify-content: flex-start; gap: 0;">
                            @forelse($trendingHotProducts->take(7) as $index => $product)
                                <div class="trending-item" data-index="{{ $index }}" style="display: {{ $index < 3 ? 'flex' : 'none' }}; margin-bottom: 20px; flex-shrink: 0; flex-direction: row; align-items: stretch; gap: 12px; min-height: 110px;">
                                    <div style="position: relative; width: 110px; flex-shrink: 0;">
                                        @if($product->url)
                                        <a href="{{ $product->url }}" target="_blank" rel="noopener noreferrer" style="display: block; position: relative;">
                                        @else
                                        <div style="display: block; position: relative;">
                                        @endif
                                            @if($product->image)
                                                @php
                                                    $imageUrl = $product->image;
                                                    if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                                                        $imageUrl = asset($product->image);
                                                    }
                                                @endphp
                                                <img src="{{ $imageUrl }}" 
                                                     alt="{{ $product->name }}" 
                                                     style="width: 100%; height: 110px; object-fit: cover; object-position: center; border-radius: 4px; display: block;"
                                                     loading="lazy"
                                                     onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div style="width: 100%; height: 110px; background: #f0f0f0; border-radius: 4px; display: none; align-items: center; justify-content: center;">
                                                    <i class="fas fa-image" style="color: #ccc; font-size: 24px;"></i>
                                                </div>
                                            @else
                                                <div style="width: 100%; height: 110px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-image" style="color: #ccc; font-size: 24px;"></i>
                                                </div>
                                            @endif
                                            <span style="position: absolute; top: 4px; left: 4px; background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); color: #fff; font-weight: 700; width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 14px; box-shadow: 0 3px 8px rgba(14, 165, 233, 0.4); z-index: 10; border: 2px solid rgba(255,255,255,0.3);">
                                                {{ $index + 1 }}
                                            </span>
                                        @if($product->url)
                                        </a>
                                        @else
                                        </div>
                                        @endif
                                    </div>
                                    <div style="flex: 1; min-width: 0; padding-top: 0; display: flex; flex-direction: column; justify-content: space-between;">
                                        <h4 style="margin: 0 0 8px 0; font-size: 14px; line-height: 1.4; font-weight: 700; font-family: 'Montserrat', sans-serif; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; flex: 1;">
                                            @if($product->url)
                                            <a href="{{ $product->url }}" target="_blank" rel="noopener noreferrer"
                                               style="color: #333; text-decoration: none; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; transition: color 0.2s; word-wrap: break-word;" onmouseover="this.style.color='#f8c2eb'" onmouseout="this.style.color='#333'">
                                                {{ $product->name }}
                                            </a>
                                            @else
                                            <span style="color: #333; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; word-wrap: break-word;">
                                                {{ $product->name }}
                                            </span>
                                            @endif
                                        </h4>
                                        @if($product->rating && $product->rating > 0)
                                        <div style="margin-bottom: 6px; display: flex; align-items: center; gap: 4px; flex-shrink: 0;">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= floor($product->rating))
                                                    <i class="fas fa-star" style="color: #ffc107; font-size: 11px;"></i>
                                                @elseif($i - 0.5 <= $product->rating)
                                                    <i class="fas fa-star-half-alt" style="color: #ffc107; font-size: 11px;"></i>
                                                @else
                                                    <i class="far fa-star" style="color: #ddd; font-size: 11px;"></i>
                                                @endif
                                            @endfor
                                            <span style="font-size: 11px; color: #666; margin-left: 4px; font-weight: 600;">{{ number_format($product->rating, 1) }}</span>
                                        </div>
                                        @endif
                                        @if($product->brand)
                                        <div style="font-size: 11px; color: #888; text-transform: uppercase; letter-spacing: 0.3px; line-height: 1.2; flex-shrink: 0;">
                                            <i class="fas fa-tag" style="margin-right: 4px; font-size: 10px;"></i>
                                            {{ $product->brand }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div style="padding: 20px; text-align: center; color: #999;">
                                    <p style="font-size: 14px;">Chưa có sản phẩm trending</p>
                                    <p style="font-size: 12px; margin-top: 5px;">Thêm sản phẩm tại Admin → Hot Products</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
    </section>

    <!-- Editor's Picks Section (Full Width) -->
    <section style="padding: 40px 0; background: transparent;">
        <div class="container-wrapper">
            <div class="af-title-subtitle-wrap" style="margin-bottom: 25px; display: flex; align-items: center; justify-content: space-between;">
                <h4 class="widget-title header-after1" style="margin: 0; font-size: 26px; font-weight: 800; font-family: 'Montserrat', sans-serif; background: linear-gradient(135deg, #0ea5e9 0%, #8b5cf6 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; position: relative; padding-bottom: 10px; flex: 1;">
                    <span style="position: absolute; bottom: 0; left: 0; width: 80px; height: 4px; background: linear-gradient(to right, #0ea5e9, #8b5cf6); border-radius: 2px;"></span>
                    EDITOR'S PICKS
                </h4>
                <div style="display: flex; gap: 8px; margin-left: 20px; position: relative; z-index: 100;">
                    <button class="editors-picks-prev" type="button" style="background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); border: 1px solid rgba(14, 165, 233, 0.3); width: 36px; height: 36px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s; position: relative; z-index: 10; box-shadow: 0 2px 6px rgba(14, 165, 233, 0.2);" 
                            onmouseover="this.style.background='linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%)'; this.querySelector('i').style.color='#fff'; this.style.transform='scale(1.1)'" 
                            onmouseout="this.style.background='linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%)'; this.querySelector('i').style.color='#0ea5e9'; this.style.transform='scale(1)'">
                        <i class="fas fa-chevron-left" style="font-size: 14px; color: #0ea5e9; pointer-events: none; transition: color 0.3s;"></i>
                    </button>
                    <button class="editors-picks-next" type="button" style="background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); border: 1px solid rgba(14, 165, 233, 0.3); width: 36px; height: 36px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s; position: relative; z-index: 10; box-shadow: 0 2px 6px rgba(14, 165, 233, 0.2);" 
                            onmouseover="this.style.background='linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%)'; this.querySelector('i').style.color='#fff'; this.style.transform='scale(1.1)'" 
                            onmouseout="this.style.background='linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%)'; this.querySelector('i').style.color='#0ea5e9'; this.style.transform='scale(1)'">
                        <i class="fas fa-chevron-right" style="font-size: 14px; color: #0ea5e9; pointer-events: none; transition: color 0.3s;"></i>
                    </button>
                </div>
            </div>

            <div class="editors-picks-carousel" style="position: relative; overflow: hidden;">
                <div class="editors-picks-container" style="display: flex; gap: 20px; overflow-x: auto; scroll-behavior: smooth; scrollbar-width: none; -ms-overflow-style: none; padding-bottom: 10px;">
                    <div class="editors-picks-wrapper" style="display: flex; gap: 20px; min-width: max-content;">
                    @foreach($editorsPicks->take(10) as $post)
                        <div class="editor-pick-item" style="background: linear-gradient(to bottom, rgba(255,255,255,0.95) 0%, rgba(240,249,255,0.8) 100%); border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(14, 165, 233, 0.15), 0 2px 4px rgba(0,0,0,0.05); border: 1px solid rgba(14, 165, 233, 0.1); transition: all 0.3s ease; display: flex; flex-direction: column; width: 350px; flex-shrink: 0;"
                             onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 8px 20px rgba(14, 165, 233, 0.25), 0 4px 8px rgba(0,0,0,0.1)';"
                             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(14, 165, 233, 0.15), 0 2px 4px rgba(0,0,0,0.05)'">
                            <a href="{{ route('posts.show', [$post->published_at->year, str_pad($post->published_at->month, 2, '0', STR_PAD_LEFT), $post->slug]) }}" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; height: 100%;">
                                <div style="position: relative; overflow: hidden; flex-shrink: 0;">
                                    @if($post->featured_image)
                                        <img src="{{ $post->featured_image }}" 
                                             alt="{{ $post->title }}" 
                                             style="width: 100%; height: 220px; object-fit: cover; object-position: center; transition: transform 0.3s; display: block;"
                                             loading="lazy"
                                             onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div style="width: 100%; height: 220px; background: #f0f0f0; display: none; align-items: center; justify-content: center;">
                                            <i class="fas fa-image" style="font-size: 48px; color: #ccc;"></i>
                                        </div>
                                        @else
                                        <div style="width: 100%; height: 220px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-image" style="font-size: 48px; color: #ccc;"></i>
                                        </div>
                                    @endif
                                    
                                    <!-- Min read badge (yellow oval) -->
                                    <div style="position: absolute; top: 12px; left: 12px; z-index: 10;">
                                        <span style="background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%); color: #fff; padding: 5px 12px; border-radius: 20px; font-size: 10px; font-weight: 700; display: inline-block; box-shadow: 0 2px 6px rgba(245, 158, 11, 0.3);">
                                            {{ ceil(str_word_count(strip_tags($post->content)) / 200) }} min read
                                        </span>
                                    </div>
                                    
                                    <!-- Categories (pink labels on image) -->
                                    @if($post->categories->count() > 0)
                                    <div style="position: absolute; top: 12px; right: 12px; z-index: 10; display: flex; flex-direction: column; gap: 5px; align-items: flex-end;">
                                        @foreach($post->categories->take(3) as $category)
                                            <span style="background: linear-gradient(135deg, #0ea5e9 0%, #8b5cf6 100%); color: #fff; padding: 4px 10px; border-radius: 6px; font-size: 10px; font-weight: 700; display: inline-block; white-space: nowrap; box-shadow: 0 2px 6px rgba(14, 165, 233, 0.3);">
                                                {{ $category->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                        @endif
                                </div>
                                
                                <!-- Content below image -->
                                <div style="padding: 20px; flex: 1; display: flex; flex-direction: column;">
                                    <h4 style="margin: 0 0 12px 0; font-size: 18px; line-height: 1.4; font-weight: 700; font-family: 'Montserrat', sans-serif; color: #1e293b; min-height: 50px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ Str::limit($post->title, 75) }}
                                    </h4>
                                    @if($post->rating)
                                    <div style="margin-bottom: 8px; display: flex; align-items: center; gap: 4px;">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($post->rating))
                                                <i class="fas fa-star" style="color: #ffc107; font-size: 12px;"></i>
                                            @elseif($i - 0.5 <= $post->rating)
                                                <i class="fas fa-star-half-alt" style="color: #ffc107; font-size: 12px;"></i>
                                            @else
                                                <i class="far fa-star" style="color: #ddd; font-size: 12px;"></i>
                                            @endif
                                        @endfor
                                        <span style="font-size: 12px; color: #666; margin-left: 4px; font-weight: 600;">{{ number_format($post->rating, 1) }}</span>
                                    </div>
                                    @endif
                                    <div style="font-size: 11px; color: #666; text-transform: uppercase; letter-spacing: 0.3px; display: flex; align-items: center; gap: 5px; margin-top: auto;">
                                        <i class="far fa-clock" style="font-size: 10px;"></i>
                                        <span>{{ $post->published_at->format('F j, Y') }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Posts Section (Full Width) -->
    <section style="padding: 40px 0; background: transparent;">
        <div class="container-wrapper">
            <div class="af-title-subtitle-wrap" style="margin-bottom: 25px;">
                <h4 class="widget-title header-after1" style="margin: 0; font-size: 24px; font-weight: 700; font-family: 'Montserrat', sans-serif; color: #333; position: relative; padding-bottom: 10px;">
                    <span style="position: absolute; bottom: 0; left: 0; width: 60px; height: 3px; background: linear-gradient(to right, #f8c2eb, #f8c2eb);"></span>
                    FEATURED POSTS
                </h4>
            </div>

            <div class="featured-posts-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                @foreach($featuredPosts->take(9) as $post)
                    <div class="featured-post-item" style="background: linear-gradient(to bottom, rgba(255,255,255,0.95) 0%, rgba(240,249,255,0.8) 100%); border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(14, 165, 233, 0.15), 0 2px 4px rgba(0,0,0,0.05); border: 1px solid rgba(14, 165, 233, 0.1); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 8px 20px rgba(14, 165, 233, 0.25), 0 4px 8px rgba(0,0,0,0.1)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(14, 165, 233, 0.15), 0 2px 4px rgba(0,0,0,0.05)'">
                        <a href="{{ route('posts.show', [$post->published_at->year, str_pad($post->published_at->month, 2, '0', STR_PAD_LEFT), $post->slug]) }}" style="text-decoration: none; color: inherit; display: block;">
                            <!-- Image -->
                            <div style="width: 100%; height: 200px; overflow: hidden; background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);">
                                @if($post->featured_image)
                                    <img src="{{ $post->featured_image }}" 
                                         alt="{{ $post->title }}" 
                                         style="width: 100%; height: 100%; object-fit: cover; object-position: center; transition: transform 0.3s; display: block;"
                                         loading="lazy"
                                         onmouseover="this.style.transform='scale(1.05)'" 
                                         onmouseout="this.style.transform='scale(1)'"
                                         onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div style="width: 100%; height: 100%; display: none; align-items: center; justify-content: center; background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);">
                                        <i class="fas fa-image" style="font-size: 36px; color: #0ea5e9; opacity: 0.4;"></i>
                                    </div>
                                @else
                                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);">
                                        <i class="fas fa-image" style="font-size: 36px; color: #0ea5e9; opacity: 0.4;"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Content -->
                            <div style="padding: 20px;">
                                <h4 style="margin: 0 0 12px 0; font-size: 17px; line-height: 1.4; font-weight: 700; font-family: 'Montserrat', sans-serif; color: #1e293b; min-height: 44px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ Str::limit($post->title, 80) }}
                                </h4>
                                @if($post->rating)
                                <div style="margin-bottom: 8px; display: flex; align-items: center; gap: 4px;">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($post->rating))
                                            <i class="fas fa-star" style="color: #ffc107; font-size: 12px;"></i>
                                        @elseif($i - 0.5 <= $post->rating)
                                            <i class="fas fa-star-half-alt" style="color: #ffc107; font-size: 12px;"></i>
                                        @else
                                            <i class="far fa-star" style="color: #ddd; font-size: 12px;"></i>
                                        @endif
                                    @endfor
                                    <span style="font-size: 12px; color: #666; margin-left: 4px; font-weight: 600;">{{ number_format($post->rating, 1) }}</span>
                                </div>
                                @endif
                                <div style="font-size: 11px; color: #888; text-transform: uppercase; letter-spacing: 0.3px; display: flex; align-items: center; gap: 5px;">
                                    <i class="far fa-clock" style="font-size: 10px;"></i>
                                    <span>{{ $post->published_at->format('F j, Y') }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Blog Posts List Section -->
    <section class="aft-main-breadcrumb-wrapper" style="padding: 40px 0; background: transparent;">
        <div class="container-wrapper">
            <div class="af-container-row clearfix" style="display: flex; flex-wrap: wrap; margin: 0 -15px;">
                <!-- Main Content Column (75%) -->
                <div id="primary" class="content-area" style="width: 75%; padding: 0 15px; float: left;">
                    <main id="main" class="site-main">
                        <div id="aft-archive-wrapper" class="aft-archive-wrapper clearfix" style="margin: 0;">
                            @foreach($latestPosts->slice(7) as $post)
                                <article class="latest-posts-list archive-layout-list" style="margin-bottom: 40px; padding: 30px; padding-bottom: 40px; border-bottom: 2px solid rgba(14, 165, 233, 0.15); background: linear-gradient(to bottom, rgba(255,255,255,0.6) 0%, rgba(240,249,255,0.4) 100%); border-radius: 12px; backdrop-blur-sm; transition: all 0.3s ease;"
                                        onmouseover="this.style.transform='translateX(8px)'; this.style.boxShadow='0 4px 16px rgba(14, 165, 233, 0.15)'"
                                        onmouseout="this.style.transform='translateX(0)'; this.style.boxShadow='none'">
                                    <div class="archive-list-post list-style">
                                        <div class="af-double-column list-style clearfix aft-list-show-image" style="display: flex; align-items: flex-start; gap: 25px;">
                                            <!-- Image Column -->
                                            <div class="col-3 float-l pos-rel read-img read-bg-img post-image-col" style="width: 300px; flex-shrink: 0; position: relative;">
                                                <a class="aft-post-image-link" href="{{ route('posts.show', [$post->published_at->year, str_pad($post->published_at->month, 2, '0', STR_PAD_LEFT), $post->slug]) }}" style="display: block; position: relative;">
                                                    @if($post->featured_image)
                                                        <img width="300" height="300" src="{{ $post->featured_image }}" 
                                                             alt="{{ $post->title }}" 
                                                             class="wp-post-image" 
                                                             style="width: 100%; height: 300px; object-fit: cover; object-position: center; border-radius: 6px; display: block;"
                                                             loading="lazy"
                                                             onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                        <div style="width: 100%; height: 300px; background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); border-radius: 8px; display: none; align-items: center; justify-content: center;">
                                                            <i class="fas fa-image" style="font-size: 48px; color: #0ea5e9; opacity: 0.4;"></i>
                                                        </div>
                                                    @else
                                                        <div style="width: 100%; height: 300px; background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                            <i class="fas fa-image" style="font-size: 48px; color: #0ea5e9; opacity: 0.4;"></i>
                                                        </div>
                                                    @endif
                                                    
                                                    <!-- Category and Min Read Overlay -->
                                                    <div class="category-min-read-wrap af-cat-widget-carousel" style="position: absolute; top: 12px; left: 12px; z-index: 10;">
                                                        <div class="post-format-and-min-read-wrap" style="margin-bottom: 8px;">
                                                            <span class="min-read" style="background: rgba(0,0,0,0.75); color: #fff; padding: 5px 10px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block;">
                                                                {{ ceil(str_word_count(strip_tags($post->content)) / 200) }} min read
                                                            </span>
                                                        </div>
                                                        @if($post->categories->count() > 0)
                                                        <div class="read-categories">
                                                            <ul class="cat-links" style="list-style: none; padding: 0; margin: 0; display: flex; flex-wrap: wrap; gap: 5px;">
                                                                @foreach($post->categories->take(3) as $category)
                                                                    <li class="meta-category" style="display: inline-block;">
                                                                        <a class="chromenews-categories category-color-1" 
                                                                           href="{{ route('categories.show', $category->slug) }}"
                                                                           style="background: linear-gradient(135deg, #0ea5e9 0%, #8b5cf6 100%); color: #fff; padding: 5px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; text-decoration: none; display: inline-block; box-shadow: 0 2px 6px rgba(14, 165, 233, 0.3); transition: all 0.3s;"
                                                                           onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 3px 8px rgba(14, 165, 233, 0.4)'"
                                                                           onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 2px 6px rgba(14, 165, 233, 0.3)'">
                                                                            {{ $category->name }}
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </a>
                                            </div>
                                            
                                            <!-- Content Column -->
                                            <div class="col-66 float-l pad read-details color-tp-pad" style="flex: 1; min-width: 0;">
                                                <div class="read-title" style="margin-bottom: 15px;">
                                                    <h4 style="margin: 0; font-size: 24px; line-height: 1.3; font-weight: 700; font-family: 'Montserrat', sans-serif;">
                                                        <a href="{{ route('posts.show', [$post->published_at->year, str_pad($post->published_at->month, 2, '0', STR_PAD_LEFT), $post->slug]) }}" 
                                                           style="color: #1e293b; text-decoration: none; transition: all 0.3s; font-weight: 700;" onmouseover="this.style.color='#0ea5e9'; this.style.textShadow='0 2px 4px rgba(14, 165, 233, 0.2)'" onmouseout="this.style.color='#1e293b'; this.style.textShadow='none'">
                                                            {{ $post->title }}
                                                        </a>
                                                    </h4>
                                                </div>
                                                
                                                <div class="post-item-metadata entry-meta" style="margin-bottom: 15px; font-size: 13px; color: #666;">
                                                    <span class="author-links" style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                                                        <span class="item-metadata posts-author byline" style="display: flex; align-items: center; gap: 5px;">
                                                            <i class="far fa-user-circle" style="font-size: 14px;"></i>
                                                            <span style="text-transform: uppercase; font-weight: 600;">
                                                                {{ strtoupper($post->author->name) }}
                                                            </span>
                                                        </span>
                                                        <span class="item-metadata posts-date" style="display: flex; align-items: center; gap: 5px; text-transform: uppercase;">
                                                            <i class="far fa-clock" aria-hidden="true" style="font-size: 13px;"></i>
                                                            {{ $post->published_at->format('F j, Y') }}
                                                        </span>
                                                        @if($post->rating)
                                                        <span class="item-metadata posts-rating" style="display: flex; align-items: center; gap: 4px;">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                @if($i <= floor($post->rating))
                                                                    <i class="fas fa-star" style="color: #ffc107; font-size: 14px;"></i>
                                                                @elseif($i - 0.5 <= $post->rating)
                                                                    <i class="fas fa-star-half-alt" style="color: #ffc107; font-size: 14px;"></i>
                                                                @else
                                                                    <i class="far fa-star" style="color: #ddd; font-size: 14px;"></i>
                                                                @endif
                                                            @endfor
                                                            <span style="font-size: 13px; color: #666; margin-left: 2px; font-weight: 600;">{{ number_format($post->rating, 1) }}</span>
                                                        </span>
                                                        @endif
                                                    </span>
                                                </div>
                                                
                                                <div class="read-description full-item-description" style="margin-bottom: 20px;">
                                                    <div class="post-description" style="font-size: 15px; line-height: 1.7; color: #555; font-family: 'Montserrat', sans-serif;">
                                                        <p style="margin: 0 0 15px 0;">
                                                            {{ Str::limit(strip_tags($post->content), 200) }}
                                                        </p>
                                                        <div class="aft-readmore-wrapper" style="margin-top: 15px;">
                                                            <a href="{{ route('posts.show', [$post->published_at->year, str_pad($post->published_at->month, 2, '0', STR_PAD_LEFT), $post->slug]) }}" 
                                                               class="aft-readmore" 
                                                               style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); color: #fff; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block; transition: all 0.3s; box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);"
                                                               onmouseover="this.style.background='linear-gradient(135deg, #0284c7 0%, #0369a1 100%)'; this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 16px rgba(14, 165, 233, 0.4)'" 
                                                               onmouseout="this.style.background='linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(14, 165, 233, 0.3)'">
                                                                Read More
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="clear: both;"></div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </main>
                </div>
                
                <!-- Sidebar Column (25%) -->
                <aside id="secondary" class="widget-area" style="width: 25%; padding: 0 15px; float: left;">
                    <!-- Search Widget -->
                    <div class="widget chromenews-widget" style="margin-bottom: 30px; background: #f5f5f5; padding: 20px; border-radius: 0;">
                        <form role="search" method="get" action="{{ route('posts.index') }}" style="display: flex; gap: 0; align-items: stretch; background: #f0f0f0; border-radius: 6px; padding: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                            <input type="search" name="search" placeholder="Search..." required 
                                   style="flex: 1; padding: 12px 16px; border: 2px solid rgba(14, 165, 233, 0.2); background: #fff; border-radius: 8px 0 0 8px; font-size: 14px; font-family: 'Montserrat', sans-serif; outline: none; min-width: 0; transition: all 0.3s;"
                                   onfocus="this.style.borderColor='#0ea5e9'; this.style.boxShadow='0 0 0 3px rgba(14, 165, 233, 0.1)'" onblur="this.style.borderColor='rgba(14, 165, 233, 0.2)'; this.style.boxShadow='none'">
                            <button type="submit" 
                                    style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); color: #fff; padding: 12px 24px; border: none; border-radius: 0 8px 8px 0; font-weight: 700; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; transition: all 0.3s; font-family: 'Montserrat', sans-serif; white-space: nowrap; flex-shrink: 0; box-shadow: 0 2px 8px rgba(14, 165, 233, 0.3);"
                                    onmouseover="this.style.background='linear-gradient(135deg, #0284c7 0%, #0369a1 100%)'; this.style.transform='scale(1.02)'; this.style.boxShadow='0 4px 12px rgba(14, 165, 233, 0.4)'" onmouseout="this.style.background='linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%)'; this.style.transform='scale(1)'; this.style.boxShadow='0 2px 8px rgba(14, 165, 233, 0.3)'">
                                SEARCH
                            </button>
                        </form>
                    </div>
                    
                    <!-- Our Sources -->
                    <div class="widget chromenews-widget" style="margin-bottom: 20px; background: #fafafa; padding: 25px; border-radius: 8px;">
                        <p style="text-align: center; margin: 0 0 15px 0; font-weight: 800; font-size: 18px; font-family: 'Montserrat', sans-serif; background: linear-gradient(135deg, #0ea5e9 0%, #8b5cf6 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; text-transform: uppercase; letter-spacing: 0.5px;">
                            Our Sources
                        </p>
                        <div style="display: flex; flex-direction: column; gap: 15px; align-items: center;">
                            <div style="text-align: center;">
                                <img src="{{ asset('images/mayo-clinic-logo-8.webp') }}" alt="Mayo Clinic" 
                                     style="max-width: 255px; height: auto; display: block; margin: 0 auto;" 
                                     onerror="this.style.display='none'">
                            </div>
                            <div style="text-align: center;">
                                <img src="{{ asset('images/medline-plus-logo-2.webp') }}" alt="MedlinePlus" 
                                     style="max-width: 238px; height: auto; display: block; margin: 0 auto;" 
                                     onerror="this.style.display='none'">
                            </div>
                            <div style="text-align: center;">
                                <img src="{{ asset('images/healthline-logo-1.webp') }}" alt="Healthline" 
                                     style="max-width: 240px; height: auto; display: block; margin: 0 auto;" 
                                     onerror="this.style.display='none'">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sponsored Advertising Content -->
                    <div class="widget chromenews-widget" style="margin-bottom: 20px; background: #fafafa; padding: 25px; border-radius: 8px;">
                        <p style="text-align: center; margin: 0 0 15px 0; font-size: 14px; font-weight: 700; font-family: 'Montserrat', sans-serif; color: #333; text-transform: uppercase; letter-spacing: 0.5px;">
                            SPONSORED ADVERTISING CONTENT
                        </p>
                        <div style="text-align: center; margin-bottom: 15px;">
                            <img src="{{ asset('images/cropped-cropped-sr.jpg') }}" alt="Sponsored" 
                                 style="max-width: 100%; height: auto; border-radius: 8px; display: block; margin: 0 auto;" 
                                 onerror="this.style.display='none'">
                        </div>
                        <p style="text-align: center; margin: 0; font-size: 12px; color: #666; font-family: 'Montserrat', sans-serif; line-height: 1.5;">
                            Sources used for research purposes. smartestreviews.net are not officially affiliated with these sites.
                        </p>
                    </div>
                    
                    <!-- Recent Posts Widget -->
                    <div class="widget chromenews-widget" style="margin-bottom: 30px; background: #fafafa; padding: 25px; border-radius: 8px;">
                        <h2 class="widget-title" style="margin: 0 0 20px 0; font-size: 18px; font-weight: 700; font-family: 'Montserrat', sans-serif; color: #333; text-transform: uppercase; letter-spacing: 0.5px; padding-bottom: 10px; border-bottom: 3px solid #f8c2eb;">
                            Recent Posts
                        </h2>
                        <ul class="wp-block-latest-posts__list" style="list-style: none; padding: 0; margin: 0;">
                            @foreach($latestPosts->take(5) as $index => $post)
                                <li style="margin-bottom: 12px;">
                                    <a href="{{ route('posts.show', [$post->published_at->year, str_pad($post->published_at->month, 2, '0', STR_PAD_LEFT), $post->slug]) }}" 
                                       class="wp-block-latest-posts__post-title"
                                       style="color: #333; text-decoration: none; font-size: 14px; line-height: 1.5; font-weight: 600; font-family: 'Montserrat', sans-serif; transition: color 0.3s; display: block;"
                                       onmouseover="this.style.color='#f8c2eb'" onmouseout="this.style.color='#333'">
                                        {{ Str::limit($post->title, 70) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <!-- Recent Comments Widget -->
                    <div class="widget chromenews-widget" style="margin-bottom: 30px; background: #fafafa; padding: 25px; border-radius: 8px;">
                        <h2 class="widget-title" style="margin: 0 0 20px 0; font-size: 18px; font-weight: 700; font-family: 'Montserrat', sans-serif; color: #333; text-transform: uppercase; letter-spacing: 0.5px; padding-bottom: 10px; border-bottom: 3px solid #f8c2eb;">
                            Recent Comments
                        </h2>
                        <ol class="wp-block-latest-comments" style="list-style: decimal; padding-left: 20px; margin: 0; font-size: 13px; color: #666; font-family: 'Montserrat', sans-serif;">
                            @php
                                // Sample comments for display (replace with actual Comment model when available)
                                $sampleComments = [
                                    ['author' => 'Alexander Peter Culver', 'post_title' => 'Top 5 Multivitamin Supplements Of 2025', 'post_slug' => 'top-5-multivitamin-supplements-of-2025'],
                                    ['author' => 'Boby', 'post_title' => 'Top 5 Probiotics That Will Transform Your Gut Health', 'post_slug' => 'top-5-probiotics-that-will-transform-your-gut-health'],
                                    ['author' => 'Bel', 'post_title' => 'Top 5 Greens Supplements that will transform your Health in 2025', 'post_slug' => 'top-5-greens-supplements-that-will-transform-your-health-in-2025'],
                                    ['author' => 'Gordon H - Seattle Washington', 'post_title' => 'The Top 4 Best Portable EV Chargers of 2024', 'post_slug' => 'the-top-4-best-portable-ev-chargers-of-2024'],
                                    ['author' => 'Valeria Jones', 'post_title' => 'Best Home Remedy for Vaginal Yeast Infections', 'post_slug' => 'best-home-remedy-for-vaginal-yeast-infections'],
                                ];
                            @endphp
                            @foreach($sampleComments as $comment)
                                @php
                                    // Try to find the post by slug or title
                                    $post = \App\Models\Post::where('slug', $comment['post_slug'])
                                        ->orWhere('title', 'like', '%' . $comment['post_title'] . '%')
                                        ->published()
                                        ->first();
                                @endphp
                                <li class="wp-block-latest-comments__comment" style="margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #e0e0e0;">
                                    <article>
                                        <footer class="wp-block-latest-comments__comment-meta" style="font-size: 13px;">
                                            <span class="wp-block-latest-comments__comment-author" style="color: #333; font-weight: 600;">{{ $comment['author'] }}</span> on 
                                            @if($post)
                                                <a class="wp-block-latest-comments__comment-link" 
                                                   href="{{ route('posts.show', [$post->published_at->year, str_pad($post->published_at->month, 2, '0', STR_PAD_LEFT), $post->slug]) }}"
                                                   style="color: #666; text-decoration: none; transition: color 0.3s;"
                                                   onmouseover="this.style.color='#f8c2eb'" onmouseout="this.style.color='#666'">
                                                    {{ Str::limit($post->title, 60) }}
                                                </a>
                                            @else
                                                <a href="#" style="color: #666; text-decoration: none; transition: color 0.3s;"
                                                   onmouseover="this.style.color='#f8c2eb'" onmouseout="this.style.color='#666'">
                                                    {{ Str::limit($comment['post_title'], 60) }}
                                                </a>
                                            @endif
                                        </footer>
                                    </article>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                    
                    <!-- Archives Widget -->
                    <div class="widget chromenews-widget" style="margin-bottom: 30px; background: #f5f5f5; padding: 20px; border-radius: 0;">
                        <h2 class="widget-title" style="margin: 0 0 15px 0; font-size: 16px; font-weight: 700; font-family: 'Montserrat', sans-serif; color: #333; text-transform: uppercase; letter-spacing: 0.5px; padding-bottom: 8px; border-bottom: 2px solid #f8c2eb;">
                            ARCHIVES
                        </h2>
                        <ul class="wp-block-archives-list wp-block-archives" style="list-style: none; padding: 0; margin: 0;">
                            @php
                                $dbDriver = \DB::connection()->getDriverName();
                                
                                if ($dbDriver === 'sqlite') {
                                    // SQLite syntax - hiển thị tất cả các tháng có bài viết
                                    $archives = \App\Models\Post::published()
                                        ->selectRaw('CAST(strftime("%Y", published_at) AS INTEGER) as year, CAST(strftime("%m", published_at) AS INTEGER) as month, COUNT(*) as count')
                                        ->whereNotNull('published_at')
                                        ->groupByRaw('strftime("%Y", published_at), strftime("%m", published_at)')
                                        ->orderByRaw('strftime("%Y", published_at) DESC, strftime("%m", published_at) DESC')
                                        ->get();
                                } else {
                                    // MySQL/PostgreSQL syntax - hiển thị tất cả các tháng có bài viết
                                    $archives = \App\Models\Post::published()
                                        ->selectRaw('YEAR(published_at) as year, MONTH(published_at) as month, COUNT(*) as count')
                                        ->whereNotNull('published_at')
                                        ->groupBy('year', 'month')
                                        ->orderBy('year', 'desc')
                                        ->orderBy('month', 'desc')
                                        ->get();
                                }
                            @endphp
                            @forelse($archives as $index => $archive)
                                <li style="padding: 8px 0; {{ $index < $archives->count() - 1 ? 'border-bottom: 1px solid #e0e0e0;' : '' }}">
                                    <a href="{{ route('posts.index', ['year' => $archive->year, 'month' => str_pad($archive->month, 2, '0', STR_PAD_LEFT)]) }}" 
                                       style="color: #333; text-decoration: none; font-size: 14px; font-family: 'Montserrat', sans-serif; transition: color 0.3s; display: flex; justify-content: space-between; align-items: center;"
                                       onmouseover="this.style.color='#f8c2eb'" onmouseout="this.style.color='#333'">
                                        <span>{{ \Carbon\Carbon::create($archive->year, $archive->month, 1)->format('F Y') }}</span>
                                        <span style="color: #999; font-size: 13px; font-weight: 500;">({{ $archive->count }})</span>
                                    </a>
                                </li>
                            @empty
                                <li style="padding: 8px 0; color: #999; font-style: italic; font-size: 14px;">
                                    No archives available
                                </li>
                            @endforelse
                        </ul>
                    </div>
                    
                    <!-- Categories Widget -->
                    <div class="widget chromenews-widget" style="margin-bottom: 40px; background: #f5f5f5; padding: 20px; border-radius: 0;">
                        <h2 class="widget-title" style="margin: 0 0 15px 0; font-size: 16px; font-weight: 700; font-family: 'Montserrat', sans-serif; color: #333; text-transform: uppercase; letter-spacing: 0.5px; padding-bottom: 8px; border-bottom: 2px solid #f8c2eb;">
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
    </section>
</div>

<style>
    @keyframes scroll-left {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    
    .marquee {
        animation: scroll-left 40s linear infinite;
    }
    
    .marquee:hover {
        animation-play-state: paused;
    }
    
    @keyframes ripple {
        0% { transform: scale(0); opacity: 1; }
        100% { transform: scale(4); opacity: 0; }
    }
    
    .editor-pick-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    }
    
    .editor-pick-item:hover img {
        transform: scale(1.05);
    }
    
    .editors-picks-container::-webkit-scrollbar {
        display: none;
    }
    
    .trending-prev:hover,
    .trending-next:hover,
    .editors-picks-prev:hover,
    .editors-picks-next:hover {
        background: #e8e8e8 !important;
        border-color: #d0d0d0 !important;
    }
    
    /* Featured Slideshow Hover Effects */
    .featured-slide-link {
        cursor: pointer !important;
    }
    
    .featured-slide-link:hover .featured-slide-title {
        text-decoration-color: #fff !important;
        text-decoration-thickness: 2px !important;
        text-underline-offset: 4px !important;
    }
    
    .featured-slide-link:hover {
        opacity: 0.95;
    }
    
    .featured-slide-link:hover .featured-slide-image {
        filter: brightness(1.1);
    }
    
    /* Prevent horizontal scroll on mobile */
    body {
        overflow-x: hidden;
    }
    
    /* Responsive */
    @media (max-width: 992px) {
        .aft-main-content[style*="width: 75%"],
        .aft-trending-part[style*="width: 25%"] {
            width: 100% !important;
            float: none !important;
        }
        
        .aft-trending-part[style*="width: 25%"] {
            margin-top: 40px;
        }
        
        .editor-pick-item {
            min-width: calc(50% - 10px) !important;
        }
        
        /* Featured Posts Grid */
        .featured-posts-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 15px !important;
        }
        
        /* Main Content Column */
        #primary[style*="width: 75%"],
        #secondary[style*="width: 25%"] {
            width: 100% !important;
            float: none !important;
        }
        
        #secondary[style*="width: 25%"] {
            margin-top: 30px !important;
        }
    }
    
    @media (max-width: 768px) {
        .editor-pick-item {
            min-width: calc(100% - 20px) !important;
            width: calc(100% - 20px) !important;
        }
        
        .featured-banner-item img,
        .featured-banner-item div[style*="height: 450px"] {
            height: 250px !important;
        }
        
        /* Featured Posts Grid - 1 column on mobile */
        .featured-posts-grid {
            grid-template-columns: 1fr !important;
            gap: 20px !important;
        }
        
        /* Featured Posts Title */
        .widget-title.header-after1 {
            font-size: 20px !important;
        }
        
        /* Featured Posts Image Height */
        .featured-post-item div[style*="height: 200px"] {
            height: 180px !important;
        }
        
        /* Breaking News Marquee */
        .exclusive-now {
            padding: 8px 16px !important;
            font-size: 11px !important;
        }
        
        .exclusive-slides {
            padding-left: 10px !important;
        }
        
        .circle-marq {
            width: 30px !important;
            height: 30px !important;
            margin-right: 8px !important;
        }
        
        .marquee a {
            margin-right: 30px !important;
            font-size: 12px !important;
        }
        
        /* Editor's Picks Title and Buttons */
        .af-title-subtitle-wrap {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 15px !important;
        }
        
        .af-title-subtitle-wrap > div {
            margin-left: 0 !important;
        }
        
        /* Container Padding */
        .container-wrapper {
            padding: 0 10px !important;
        }
        
        /* Main Content Padding */
        .af-container-row.clearfix {
            margin: 0 -10px !important;
        }
        
        .aft-main-content[style*="padding: 0 15px"],
        #primary[style*="padding: 0 15px"],
        #secondary[style*="padding: 0 15px"],
        .aft-main-content[style*="padding: 0 15px; float: left"],
        #primary[style*="padding: 0 15px; float: left"],
        #secondary[style*="padding: 0 15px; float: left"] {
            padding: 0 10px !important;
        }
        
        /* Section Spacing */
        section[style*="padding: 40px 0"] {
            padding: 30px 0 !important;
        }
        
        /* Slideshow Height */
        .featured-slideshow[style*="height: 450px"] {
            height: 280px !important;
        }
        
        /* Editor's Picks Card Width */
        .editor-pick-item[style*="width: 350px"] {
            width: calc(100vw - 40px) !important;
            max-width: 100% !important;
        }
        
        /* Latest Posts List */
        article.latest-posts-list {
            margin-bottom: 30px !important;
            padding-bottom: 30px !important;
        }
        
        /* Post Image in List */
        .post-image-col {
            width: 100% !important;
            margin-bottom: 15px !important;
            float: none !important;
        }
        
        .read-details[style*="width: 66.666%"] {
            width: 100% !important;
            float: none !important;
        }
        
        /* Blog Posts List Section - Mobile Layout */
        .aft-main-breadcrumb-wrapper {
            padding: 30px 0 !important;
        }
        
        .af-double-column.list-style[style*="display: flex"] {
            flex-direction: column !important;
            gap: 20px !important;
        }
        
        .col-3.float-l.pos-rel[style*="width: 300px"] {
            width: 100% !important;
            float: none !important;
            margin-bottom: 0 !important;
        }
        
        .col-3.float-l.pos-rel img[style*="width: 100%"],
        .col-3.float-l.pos-rel div[style*="height: 300px"] {
            height: 200px !important;
        }
        
        .col-66.float-l.pad.read-details {
            width: 100% !important;
            float: none !important;
            flex: none !important;
        }
        
        /* Post Title */
        .read-title h4[style*="font-size: 24px"] {
            font-size: 20px !important;
            line-height: 1.4 !important;
        }
        
        /* Post Metadata */
        .post-item-metadata .author-links {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 8px !important;
        }
        
        /* Post Description */
        .read-description .post-description {
            font-size: 14px !important;
        }
        
        /* Article Spacing */
        article.latest-posts-list {
            padding-bottom: 25px !important;
            margin-bottom: 25px !important;
        }
        
        /* Sidebar Widgets */
        .widget.chromenews-widget {
            padding: 15px !important;
        }
        
        /* Search Form on Mobile */
        .widget form[style*="display: flex"] {
            flex-direction: column !important;
            gap: 8px !important;
        }
        
        .widget form input[type="search"] {
            border-radius: 4px !important;
            margin-bottom: 0 !important;
        }
        
        .widget form button[type="submit"] {
            border-radius: 4px !important;
            width: 100% !important;
            padding: 10px !important;
        }
        
        /* Our Sources Images */
        .widget .widget-title,
        .widget h2.widget-title {
            font-size: 16px !important;
        }
        
        .widget img {
            max-width: 100% !important;
        }
        
        /* Recent Posts and Comments */
        .wp-block-latest-posts__list li,
        .wp-block-latest-comments li {
            font-size: 13px !important;
        }
    }
    
    @media (max-width: 480px) {
        .featured-banner-item img,
        .featured-banner-item div[style*="height: 450px"] {
            height: 200px !important;
        }
        
        .featured-slideshow[style*="height: 450px"] {
            height: 220px !important;
        }
        
        .editor-pick-item[style*="width: 350px"] {
            width: calc(100vw - 20px) !important;
        }
        
        .container-wrapper {
            padding: 0 8px !important;
        }
        
        .af-container-row.clearfix {
            margin: 0 -8px !important;
        }
        
        .aft-main-content[style*="padding: 0 15px"],
        #primary[style*="padding: 0 15px"],
        #secondary[style*="padding: 0 15px"] {
            padding: 0 8px !important;
        }
        
        .featured-post-item div[style*="height: 200px"] {
            height: 160px !important;
        }
        
        .editor-pick-item div[style*="height: 220px"] {
            height: 180px !important;
        }
        
        /* Trending Now Section */
        .aft-trending-part {
            padding: 15px !important;
        }
        
        /* Blog Posts List - Extra Small Mobile */
        .col-3.float-l.pos-rel img[style*="height: 300px"],
        .col-3.float-l.pos-rel div[style*="height: 300px"] {
            height: 180px !important;
        }
        
        .read-title h4[style*="font-size: 24px"] {
            font-size: 18px !important;
        }
        
        .read-description .post-description {
            font-size: 13px !important;
        }
        
        article.latest-posts-list {
            padding-bottom: 20px !important;
            margin-bottom: 20px !important;
        }
        
        /* Prevent horizontal scroll */
        html, body {
            overflow-x: hidden !important;
            max-width: 100vw !important;
        }
        
        .container-wrapper {
            max-width: 100% !important;
        }
    }
</style>

<script>
    // Featured Slideshow (Alpine.js)
    function featuredSlideshow(totalSlides) {
        return {
            currentSlide: 0,
            totalSlides: totalSlides,
            autoplayInterval: null,
            
            init() {
                // Auto-play slideshow (change slide every 8 seconds)
                this.autoplayInterval = setInterval(() => {
                    this.nextSlide();
                }, 8000);
            },
            
            nextSlide() {
                this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
            },
            
            previousSlide() {
                this.currentSlide = this.currentSlide === 0 ? this.totalSlides - 1 : this.currentSlide - 1;
            },
            
            goToSlide(index) {
                this.currentSlide = index;
                // Reset autoplay when manually changing slide
                if (this.autoplayInterval) {
                    clearInterval(this.autoplayInterval);
                    this.autoplayInterval = setInterval(() => {
                        this.nextSlide();
                    }, 8000);
                }
            }
        }
    }
    
    // Trending carousel navigation (for Hot Products in TRENDING NOW)
    let currentTrendingIndex = 0;
    const trendingItems = document.querySelectorAll('.trending-item');
    const totalTrending = trendingItems.length;
    const itemsPerView = 3;
    
    function showTrendingItems(index) {
        trendingItems.forEach((item, i) => {
            if (i >= index && i < index + itemsPerView) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }
    
    document.querySelector('.trending-prev')?.addEventListener('click', () => {
        if (currentTrendingIndex > 0) {
            currentTrendingIndex -= itemsPerView;
            if (currentTrendingIndex < 0) currentTrendingIndex = 0;
            showTrendingItems(currentTrendingIndex);
        }
    });
    
    document.querySelector('.trending-next')?.addEventListener('click', () => {
        if (currentTrendingIndex + itemsPerView < totalTrending) {
            currentTrendingIndex += itemsPerView;
            showTrendingItems(currentTrendingIndex);
        }
    });
    
    // Editor's Picks horizontal carousel
    const editorsCarousel = document.querySelector('.editors-picks-container');
    const editorsWrapper = document.querySelector('.editors-picks-wrapper');
    const editorItems = document.querySelectorAll('.editor-pick-item');
    
    function getScrollAmount() {
        if (editorItems.length > 0) {
            const itemWidth = editorItems[0].offsetWidth;
            const gap = 20;
            return itemWidth + gap;
        }
        return 370; // Default: 350px width + 20px gap
    }
    
    // Hide scrollbar
    if (editorsCarousel) {
        editorsCarousel.style.webkitScrollbar = 'none';
        // Remove wheel event listener that was blocking vertical scroll
        // Users can scroll the carousel with buttons or by dragging, not with wheel
    }
    
    // Editor's Picks navigation buttons
    const editorsPrevBtn = document.querySelector('.editors-picks-prev');
    const editorsNextBtn = document.querySelector('.editors-picks-next');
    
    if (editorsPrevBtn) {
        editorsPrevBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            if (editorsCarousel) {
                const scrollAmount = getScrollAmount();
                editorsCarousel.scrollBy({
                    left: -scrollAmount,
                    behavior: 'smooth'
                });
            }
        });
    }
    
    if (editorsNextBtn) {
        editorsNextBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            if (editorsCarousel) {
                const scrollAmount = getScrollAmount();
                editorsCarousel.scrollBy({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            }
        });
    }
</script>
@endsection
