@props(['post'])

<div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 rounded-lg p-6 mb-8">
    <div class="flex items-start justify-between mb-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">
                {{ $post->product_name ?? $post->title }}
            </h3>
            @if($post->brand)
                <p class="text-sm text-gray-600">{{ $post->brand }}</p>
            @endif
        </div>
        
        @if($post->rating)
            <div class="text-right">
                <div class="rating-stars mb-1">
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
                <span class="text-sm font-medium text-gray-900">{{ number_format($post->rating, 1) }}/5</span>
            </div>
        @endif
    </div>
    
    @if($post->price_text)
        <div class="text-2xl font-bold text-green-600 mb-4">
            {{ $post->price_text }}
        </div>
    @endif
    
    <div class="grid md:grid-cols-2 gap-6 mb-6">
        @if($post->pros && count($post->pros) > 0)
            <div>
                <h4 class="font-semibold text-green-700 mb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Pros
                </h4>
                <ul class="space-y-1">
                    @foreach($post->pros as $pro)
                        <li class="text-sm text-gray-700 flex items-start">
                            <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            {{ $pro }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        @if($post->cons && count($post->cons) > 0)
            <div>
                <h4 class="font-semibold text-red-700 mb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    Cons
                </h4>
                <ul class="space-y-1">
                    @foreach($post->cons as $con)
                        <li class="text-sm text-gray-700 flex items-start">
                            <svg class="w-4 h-4 text-red-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            {{ $con }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    
    @if($post->affiliateLinks->count() > 0)
        <div class="flex flex-wrap gap-3">
            @foreach($post->affiliateLinks->take(3) as $link)
                <a 
                    href="{{ $link->cloaked_url }}" 
                    class="btn-primary inline-flex items-center"
                    rel="sponsored nofollow"
                    target="_blank"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    {{ $link->label }}
                </a>
            @endforeach
        </div>
    @endif
</div>