@props(['productShowcases'])

@if($productShowcases->count() > 0)
    <div class="bg-white rounded-lg border border-gray-200 p-6 mb-8">
        <h3 class="text-xl font-semibold text-gray-900 mb-6">Featured Products</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($productShowcases as $product)
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow duration-300">
                    <!-- Product Image -->
                    @if($product->product_image_url)
                        <div class="aspect-w-16 aspect-h-9 mb-4">
                            <img src="{{ $product->product_image_url }}" 
                                 alt="{{ $product->product_name }}"
                                 class="w-full h-48 object-cover rounded-lg">
                        </div>
                    @endif
                    
                    <!-- Product Info -->
                    <div class="space-y-3">
                        <!-- Brand -->
                        @if($product->brand)
                            <p class="text-sm text-gray-500">{{ $product->brand }}</p>
                        @endif
                        
                        <!-- Product Name -->
                        <h4 class="text-lg font-semibold text-gray-900 line-clamp-2">
                            {{ $product->product_name }}
                        </h4>
                        
                        <!-- Description -->
                        @if($product->description)
                            <p class="text-sm text-gray-600 line-clamp-3">
                                {{ $product->description }}
                            </p>
                        @endif
                        
                        <!-- Rating -->
                        @if($product->rating)
                            <div class="flex items-center space-x-2 flex-wrap">
                                <div class="flex items-center flex-shrink-0">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($product->rating))
                                            <svg class="w-4 h-4 text-yellow-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-300 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-sm text-gray-600 whitespace-nowrap">{{ number_format($product->rating, 1) }}/5</span>
                                @if($product->review_count > 0)
                                    <span class="text-sm text-gray-500 whitespace-nowrap">({{ number_format($product->review_count) }} reviews)</span>
                                @endif
                            </div>
                        @endif
                        
                        <!-- Price -->
                        @if($product->display_price)
                            <div class="text-lg font-bold text-green-600">
                                {{ $product->display_price }}
                            </div>
                        @endif
                        
                        <!-- Features -->
                        @if($product->features && count($product->features) > 0)
                            <div class="space-y-1">
                                <h5 class="text-sm font-medium text-gray-900">Key Features:</h5>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    @foreach(array_slice($product->features, 0, 3) as $feature)
                                        <li class="flex items-start">
                                            <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <!-- Pros and Cons -->
                        <div class="grid grid-cols-1 gap-3">
                            @if($product->pros && count($product->pros) > 0)
                                <div>
                                    <h6 class="text-xs font-medium text-green-700 mb-1">Pros:</h6>
                                    <ul class="text-xs text-green-600 space-y-1">
                                        @foreach(array_slice($product->pros, 0, 2) as $pro)
                                            <li>• {{ $pro }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            
                            @if($product->cons && count($product->cons) > 0)
                                <div>
                                    <h6 class="text-xs font-medium text-red-700 mb-1">Cons:</h6>
                                    <ul class="text-xs text-red-600 space-y-1">
                                        @foreach(array_slice($product->cons, 0, 2) as $con)
                                            <li>• {{ $con }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Affiliate Button -->
                        <div class="pt-3">
                            <a href="{{ $product->cloaked_url }}" 
                               target="_blank" 
                               rel="sponsored nofollow"
                               class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center justify-center font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                {{ $product->affiliate_label ?: 'View Details' }}
                                @if($product->merchant)
                                    <span class="ml-2 text-xs opacity-75">on {{ $product->merchant }}</span>
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
