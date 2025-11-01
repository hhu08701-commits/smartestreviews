@props(['post'])

@if($post->listItems->count() > 0)
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 rounded-lg overflow-hidden mb-8">
        <div class="px-6 py-4">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                {{ $post->title }}
            </h3>
        </div>
        
        <div class="divide-y divide-blue-200">
            @foreach($post->listItems as $item)
                <div class="p-6 {{ $item->is_featured ? 'bg-blue-100' : 'bg-white' }}">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                <span class="text-lg font-bold text-gray-600">#{{ $item->rank }}</span>
                            </div>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-1">
                                        {{ $item->product_name }}
                                    </h4>
                                    @if($item->brand)
                                        <p class="text-sm text-gray-600 mb-2">{{ $item->brand }}</p>
                                    @endif
                                    
                                    @if($item->verdict)
                                        <p class="text-gray-700 mb-3">{{ $item->verdict }}</p>
                                    @endif
                                    
                                    @if($item->rating)
                                        <div class="flex items-center space-x-2 mb-3">
                                            <div class="rating-stars">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $item->rating)
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
                                            <span class="text-sm text-gray-600">{{ number_format($item->rating, 1) }}/5</span>
                                        </div>
                                    @endif
                                    
                                    @if($item->pros && count($item->pros) > 0)
                                        <div class="mb-3">
                                            <h5 class="text-sm font-medium text-green-700 mb-1">Pros:</h5>
                                            <ul class="text-sm text-gray-700 space-y-1">
                                                @foreach($item->pros as $pro)
                                                    <li class="flex items-start">
                                                        <svg class="w-3 h-3 text-green-500 mr-2 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                        </svg>
                                                        {{ $pro }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    
                                    @if($item->cons && count($item->cons) > 0)
                                        <div class="mb-3">
                                            <h5 class="text-sm font-medium text-red-700 mb-1">Cons:</h5>
                                            <ul class="text-sm text-gray-700 space-y-1">
                                                @foreach($item->cons as $con)
                                                    <li class="flex items-start">
                                                        <svg class="w-3 h-3 text-red-500 mr-2 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                        </svg>
                                                        {{ $con }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex-shrink-0 ml-4">
                                    @if($item->price_text)
                                        <div class="text-lg font-bold text-green-600 mb-2">
                                            {{ $item->price_text }}
                                        </div>
                                    @endif
                                    
                                    @if($item->affiliateLink)
                                        <a 
                                            href="{{ $item->affiliateLink->cloaked_url }}" 
                                            class="btn-primary inline-flex items-center"
                                            rel="sponsored nofollow"
                                            target="_blank"
                                        >
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                            {{ $item->affiliateLink->label }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif