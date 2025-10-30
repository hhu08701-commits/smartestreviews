@props(['title' => 'Sponsored Content'])

<div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200 p-6 mb-6">
    <div class="flex items-center mb-3">
        <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/>
        </svg>
        <h3 class="text-lg font-semibold text-blue-900">{{ $title }}</h3>
    </div>
    
    <div class="space-y-3">
        <div class="bg-white rounded-lg p-4 border border-blue-100">
            <h4 class="font-medium text-gray-900 mb-2">Amazon Associates</h4>
            <p class="text-sm text-gray-600 mb-3">
                We earn from qualifying purchases made through our affiliate links.
            </p>
            <a 
                href="https://amazon.com" 
                class="inline-flex items-center text-sm text-blue-600 hover:text-blue-700 font-medium"
                rel="sponsored nofollow"
                target="_blank"
            >
                Shop on Amazon
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>
        </div>
        
        <div class="bg-white rounded-lg p-4 border border-blue-100">
            <h4 class="font-medium text-gray-900 mb-2">Our Sources</h4>
            <p class="text-sm text-gray-600 mb-3">
                We research products from trusted retailers and manufacturers.
            </p>
            <a 
                href="{{ route('affiliate-disclosure') }}" 
                class="inline-flex items-center text-sm text-blue-600 hover:text-blue-700 font-medium"
            >
                Learn More
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</div>
