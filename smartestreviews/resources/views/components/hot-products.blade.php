@props(['hotProducts', 'showViewAllButton' => false])

<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-gray-900">🔥 Hot Products</h3>
        <span class="text-sm text-gray-500">Trending Now</span>
    </div>
    
    <div class="space-y-3">
        @foreach($hotProducts as $index => $product)
            @php
                $hasUrl = $product->url && !empty($product->url);
            @endphp
            @if($hasUrl)
                <a href="{{ $product->url }}" target="_blank" rel="noopener noreferrer" class="block">
            @endif
            <div class="flex items-start space-x-3 p-2 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer">
                <!-- Rank Badge -->
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                        @if($index === 0) bg-yellow-500 text-white
                        @elseif($index === 1) bg-gray-400 text-white
                        @elseif($index === 2) bg-orange-600 text-white
                        @else bg-gray-200 text-gray-600 @endif">
                        {{ $index + 1 }}
                    </div>
                </div>
                
                <!-- Product Image -->
                <div class="flex-shrink-0">
                    @if($product->image)
                        <img src="{{ $product->image }}" 
                             alt="{{ $product->name }}"
                             class="w-14 h-14 rounded object-cover">
                    @else
                        <div class="w-14 h-14 rounded bg-gray-200 flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                    @endif
                </div>
                
                <!-- Product Info -->
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-semibold text-gray-900 leading-tight mb-0.5" style="display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ $product->name }}
                    </h4>
                    
                    @if($product->brand)
                        <p class="text-xs text-gray-600 mb-1">{{ $product->brand }}</p>
                    @endif
                    
                    <!-- Rating Row -->
                    <div class="flex items-center gap-1.5 flex-wrap">
                        @if($product->rating)
                            <div class="flex items-center gap-0.5">
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
                            <span class="text-xs text-gray-700 font-medium">{{ number_format($product->rating, 1) }}</span>
                        @endif
                        
                        @if($product->trending)
                            <div class="flex items-center text-xs text-red-500">
                                <svg class="w-3 h-3 mr-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-medium">{{ $product->trending }}%</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @if($hasUrl)
                </a>
            @endif
        @endforeach
    </div>
    
    <!-- View All Button -->
    @if($showViewAllButton)
        <div class="mt-6 pt-4 border-t border-gray-200">
            <a href="{{ route('hot-products.index') }}" 
               class="block w-full text-center py-2 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                View All Hot Products
            </a>
        </div>
    @endif
</div>
