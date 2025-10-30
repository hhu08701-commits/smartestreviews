@props(['headings' => []])

@if(count($headings) > 0)
    <div class="bg-gray-50 rounded-lg p-6 mb-8" x-data="{ open: false }">
        <button 
            @click="open = !open" 
            class="flex items-center justify-between w-full text-left"
        >
            <h3 class="text-lg font-semibold text-gray-900">Table of Contents</h3>
            <svg 
                class="w-5 h-5 text-gray-500 transition-transform duration-200" 
                :class="{ 'rotate-180': open }"
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        
        <div x-show="open" x-transition class="mt-4">
            <nav class="space-y-2">
                @foreach($headings as $heading)
                    <a 
                        href="#{{ $heading['id'] }}" 
                        class="block text-sm text-gray-600 hover:text-gray-900 transition-colors {{ $heading['level'] == 3 ? 'ml-4' : '' }}"
                    >
                        {{ $heading['text'] }}
                    </a>
                @endforeach
            </nav>
        </div>
    </div>
@endif
