@props(['slides'])

<div class="relative bg-white rounded-lg shadow-lg overflow-hidden mb-8">
    <!-- Slideshow Container -->
    <div class="relative h-96" x-data="slideshow()">
        <!-- Slides -->
        <div class="relative h-full">
            @foreach($slides as $index => $slide)
                <div x-show="currentSlide === {{ $index }}" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="absolute inset-0">
                    
                    <!-- Slide Image -->
                    <div class="relative h-full">
                        <img src="{{ $slide->image }}" 
                             alt="{{ $slide->title }}"
                             class="w-full h-full object-cover">
                        
                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                        
                        <!-- Slide Content -->
                        <div class="absolute inset-0 flex items-center">
                            <div class="container mx-auto px-6">
                                <div class="max-w-2xl">
                                    <!-- Category Badge -->
                                    @if($slide->category)
                                        <span class="inline-block bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-medium mb-4">
                                            {{ $slide->category }}
                                        </span>
                                    @endif
                                    
                                    <!-- Title -->
                                    <h2 class="text-4xl font-bold text-white mb-4 leading-tight">
                                        {{ $slide->title }}
                                    </h2>
                                    
                                    <!-- Description -->
                                    @if($slide->description)
                                        <p class="text-xl text-gray-200 mb-6 leading-relaxed">
                                            {{ $slide->description }}
                                        </p>
                                    @endif
                                    
                                    <!-- CTA Button -->
                                    @if($slide->url)
                                        <a href="{{ $slide->url }}" 
                                           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                                            {{ $slide->button_text }}
                                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Navigation Arrows -->
        <button @click="previousSlide()" 
                class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-70 transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        
        <button @click="nextSlide()" 
                class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-70 transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
        
        <!-- Slide Indicators -->
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
            @foreach($slides as $index => $slide)
                <button @click="currentSlide = {{ $index }}"
                        :class="currentSlide === {{ $index }} ? 'bg-white' : 'bg-white bg-opacity-50'"
                        class="w-3 h-3 rounded-full transition-all"></button>
            @endforeach
        </div>
    </div>
</div>

<script>
function slideshow() {
    return {
        currentSlide: 0,
        totalSlides: {{ count($slides) }},
        
        init() {
            // Auto-play slideshow
            setInterval(() => {
                this.nextSlide();
            }, 5000); // Change slide every 5 seconds
        },
        
        nextSlide() {
            this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
        },
        
        previousSlide() {
            this.currentSlide = this.currentSlide === 0 ? this.totalSlides - 1 : this.currentSlide - 1;
        }
    }
}
</script>
