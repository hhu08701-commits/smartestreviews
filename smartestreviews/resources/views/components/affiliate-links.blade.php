@props(['affiliateLinks', 'post'])

@if($affiliateLinks && $affiliateLinks->count() > 0)
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 my-8">
        <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
            </svg>
            Where to Buy
        </h3>
        
        <div class="space-y-4">
            @foreach($affiliateLinks as $link)
                <div class="bg-white rounded-lg p-4 border border-blue-100">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">{{ $link->label }}</h4>
                            @if($link->merchant)
                                <p class="text-sm text-gray-600 mt-1">{{ $link->merchant }}</p>
                            @endif
                        </div>
                        <div class="ml-4">
                            <a href="{{ route('affiliate.redirect', $link->slug) }}" 
                               class="btn-primary inline-flex items-center"
                               @if($link->rel) rel="{{ $link->rel }}" @endif
                               target="_blank">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
