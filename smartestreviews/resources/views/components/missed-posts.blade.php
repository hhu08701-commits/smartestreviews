@php
    // Get missedPosts from view data or load directly
    if (!isset($missedPosts) || empty($missedPosts)) {
        $missedPosts = \App\Models\Post::published()
            ->with(['author', 'categories'])
            ->latest('published_at')
            ->limit(6)
            ->get();
    }
    $hasMissedPosts = $missedPosts && $missedPosts->count() > 0;
@endphp

@if($hasMissedPosts)
<!-- YOU MAY HAVE MISSED Section - Full Width -->
<section class="aft-blocks above-footer-widget-section" style="background: #fff; padding: 40px 0;">
    <div class="af-main-banner-latest-posts grid-layout chromenews-customizer">
        <div class="container-wrapper">
            <div class="widget-title-section">
                <div class="af-title-subtitle-wrap" style="position: relative; margin-bottom: 30px; display: flex; align-items: center; justify-content: center; gap: 15px;">
                    <!-- Left pink line -->
                    <span style="flex: 1; height: 2px; background: #f8c2eb; max-width: 150px;"></span>
                    
                    <!-- Title text -->
                    <h4 class="widget-title header-after1" style="margin: 0; font-size: 24px; font-weight: 700; font-family: 'Montserrat', sans-serif; color: #333; text-align: center; white-space: nowrap; flex-shrink: 0;">
                        You may have missed
                    </h4>
                    
                    <!-- Right pink line -->
                    <span style="flex: 1; height: 2px; background: #f8c2eb; max-width: 150px;"></span>
                </div>
            </div>
            <div class="af-container-row clearfix" style="display: flex; flex-wrap: wrap; margin: 0 -10px; align-items: stretch;">
                @foreach($missedPosts as $post)
                    <div class="col-3 pad float-l trending-posts-item missed-post-item" style="width: 33.333%; padding: 0 10px; float: left; margin-bottom: 20px; display: flex;">
                        <div class="aft-trending-posts list-part af-sec-post" style="display: flex; width: 100%; align-items: stretch;">
                            <div class="af-double-column list-style clearfix aft-list-show-image" style="display: flex; gap: 15px; align-items: stretch; width: 100%;">
                                <div class="col-3 float-l pos-rel read-img read-bg-img" style="width: 150px; flex-shrink: 0; align-self: flex-start;">
                                    <a href="{{ route('posts.show', [$post->published_at->year, str_pad($post->published_at->month, 2, '0', STR_PAD_LEFT), $post->slug]) }}" class="aft-post-image-link" style="display: block;">
                                        @if($post->featured_image)
                                            <img src="{{ $post->featured_image }}" 
                                                 alt="{{ $post->title }}" 
                                                 width="150" height="150"
                                                 style="width: 150px; height: 150px; object-fit: cover; object-position: center; display: block; border-radius: 4px;"
                                                 loading="lazy"
                                                 onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div style="width: 150px; height: 150px; background: #f0f0f0; border-radius: 4px; display: none; align-items: center; justify-content: center;">
                                                <i class="fas fa-image" style="font-size: 36px; color: #ccc;"></i>
                                            </div>
                                        @else
                                            <div style="width: 150px; height: 150px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-image" style="font-size: 36px; color: #ccc;"></i>
                                            </div>
                                        @endif
                                    </a>
                                </div>
                                <div class="col-66 float-l pad read-details color-tp-pad" style="flex: 1; min-width: 0; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div class="read-title" style="margin-bottom: 0; flex: 1;">
                                        <h4 style="margin: 0; font-size: 15px; font-weight: 700; line-height: 1.5; font-family: 'Montserrat', sans-serif; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; min-height: 67.5px; margin-bottom: 10px;">
                                            <a href="{{ route('posts.show', [$post->published_at->year, str_pad($post->published_at->month, 2, '0', STR_PAD_LEFT), $post->slug]) }}" 
                                               style="color: #333; text-decoration: none; transition: color 0.3s;"
                                               onmouseover="this.style.color='#f8c2eb'" onmouseout="this.style.color='#333'">
                                                {{ $post->title }}
                                            </a>
                                        </h4>
                                    </div>
                                    <div class="post-item-metadata entry-meta" style="font-size: 12px; color: #666; text-transform: uppercase; margin-top: auto; padding-top: 8px;">
                                        <span class="author-links">
                                            <span class="item-metadata posts-date">
                                                <i class="far fa-clock" aria-hidden="true" style="margin-right: 5px; font-size: 11px;"></i>
                                                <a href="{{ route('posts.index', ['year' => $post->published_at->year, 'month' => str_pad($post->published_at->month, 2, '0', STR_PAD_LEFT)]) }}" style="color: #666; text-decoration: none;">
                                                    {{ strtoupper($post->published_at->format('F j, Y')) }}
                                                </a>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<style>
    /* YOU MAY HAVE MISSED - Even alignment */
    .missed-post-item {
        min-height: 150px;
    }
    
    .missed-post-item .read-title h4 {
        word-break: break-word;
    }
    
    /* Ensure equal height rows */
    .af-container-row.clearfix {
        align-items: stretch;
    }
    
    /* Responsive for YOU MAY HAVE MISSED section */
    @media (max-width: 992px) {
        .missed-post-item {
            width: 50% !important;
        }
        
        .missed-post-item .read-title h4 {
            min-height: 90px !important;
            -webkit-line-clamp: 4 !important;
        }
    }
    
    @media (max-width: 768px) {
        .missed-post-item {
            width: 100% !important;
        }
        
        .missed-post-item .col-3 {
            width: 120px !important;
        }
        
        .missed-post-item img {
            width: 120px !important;
            height: 120px !important;
        }
        
        .missed-post-item .read-title h4 {
            min-height: auto !important;
            -webkit-line-clamp: 2 !important;
        }
        
        .widget-title.header-after1 {
            font-size: 20px !important;
        }
    }
</style>
@endif

