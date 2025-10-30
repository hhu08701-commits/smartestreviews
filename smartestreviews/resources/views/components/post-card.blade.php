@props(['post'])

<article class="card hover:shadow-lg transition-shadow duration-300">
            @if($post->featured_image_url)
                <div class="aspect-w-16 aspect-h-9">
                    <img 
                        src="{{ $post->featured_image_url }}" 
                        alt="{{ $post->featured_image_alt ?: $post->title }}"
                        class="w-full h-48 object-cover"
                        loading="lazy"
                    >
                </div>
            @endif
    
    <div class="p-6">
        <div class="flex items-center space-x-2 mb-3">
            @foreach($post->categories->take(2) as $category)
                <span 
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                >
                    {{ $category->name }}
                </span>
            @endforeach
        </div>
        
        <h2 class="text-xl font-semibold text-gray-900 mb-2 line-clamp-2">
            <a href="{{ $post->url }}" class="hover:text-blue-600 transition-colors">
                {{ $post->title }}
            </a>
        </h2>
        
        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
            {{ $post->excerpt }}
        </p>
        
        @if($post->rating)
            <div class="flex items-center space-x-2 mb-3">
                <div class="rating-stars">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $post->rating)
                            <svg class="star" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @else
                            <svg class="star-empty" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endif
                    @endfor
                </div>
                <span class="text-sm text-gray-600">{{ number_format($post->rating, 1) }}/5</span>
            </div>
        @endif
        
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <span>{{ $post->author->name }}</span>
                <span>•</span>
                <time datetime="{{ $post->published_at->toISOString() }}">
                    {{ $post->published_at->format('M j, Y') }}
                </time>
            </div>
            
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <span>{{ number_format($post->views_count) }}</span>
            </div>
        </div>
    </div>
</article>
