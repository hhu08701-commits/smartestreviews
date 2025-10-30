@props(['posts' => []])

@if($posts->count() > 0)
    <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Posts</h3>
        <div class="space-y-4">
            @foreach($posts as $post)
                <div class="flex space-x-3">
                    @if($post->featured_image)
                        <img 
                            src="{{ $post->featured_image }}" 
                            alt="{{ $post->title }}"
                            class="w-16 h-16 object-cover rounded-lg flex-shrink-0"
                            loading="lazy"
                        >
                    @endif
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-medium text-gray-900 line-clamp-2 mb-1">
                            <a href="{{ $post->url }}" class="hover:text-blue-600 transition-colors">
                                {{ $post->title }}
                            </a>
                        </h4>
                        <p class="text-xs text-gray-500">
                            {{ $post->published_at->format('M j, Y') }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
