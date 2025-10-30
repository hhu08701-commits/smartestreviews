<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Smartest Reviews - Expert Product Reviews & Comparisons')</title>
    <meta name="description" content="@yield('description', 'Discover the best products with our expert reviews, detailed comparisons, and unbiased recommendations. Find your perfect match today.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Header -->
    <header class="bg-blue-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <div class="h-8 w-8 bg-white rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-star text-blue-600"></i>
                        </div>
                        <span class="text-white font-bold text-xl">Smartest Reviews</span>
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="hidden md:flex space-x-8">
                    <a href="{{ route('home') }}" 
                       class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'bg-blue-700' : '' }}">
                        HOME
                    </a>
                    <a href="{{ route('privacy') }}" 
                       class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('privacy') ? 'bg-blue-700' : '' }}">
                        PRIVACY POLICY
                    </a>
                    <a href="{{ route('posts.index') }}" 
                       class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('posts.*') ? 'bg-blue-700' : '' }}">
                        LATEST POSTS
                    </a>
                    <a href="{{ route('how-we-rank') }}" 
                       class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('how-we-rank') ? 'bg-blue-700' : '' }}">
                        HOW WE RANK
                    </a>
                </nav>

                <!-- Right side icons -->
                <div class="flex items-center space-x-4">
                    <!-- Dark mode toggle -->
                    <button class="text-white hover:text-blue-200 transition-colors">
                        <i class="fas fa-sun"></i>
                    </button>
                    
                    <!-- Search -->
                    <button class="text-white hover:text-blue-200 transition-colors">
                        <i class="fas fa-search"></i>
                    </button>
                    
                    <!-- Notifications -->
                    <button class="text-white hover:text-blue-200 transition-colors">
                        <i class="fas fa-bell"></i>
                    </button>
                    
                    <!-- Subscribe Button -->
                    <a href="#" class="bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded-md font-semibold text-sm transition-colors">
                        SUBSCRIBE
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-white hover:text-blue-200">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-blue-700">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="{{ route('home') }}" class="text-white hover:text-blue-200 block px-3 py-2 rounded-md text-base font-medium">HOME</a>
                <a href="{{ route('privacy') }}" class="text-white hover:text-blue-200 block px-3 py-2 rounded-md text-base font-medium">PRIVACY POLICY</a>
                <a href="{{ route('posts.index') }}" class="text-white hover:text-blue-200 block px-3 py-2 rounded-md text-base font-medium">LATEST POSTS</a>
                <a href="{{ route('how-we-rank') }}" class="text-white hover:text-blue-200 block px-3 py-2 rounded-md text-base font-medium">HOW WE RANK</a>
            </div>
        </div>
    </header>

    <!-- Breadcrumbs -->
    @if(isset($breadcrumbs) && count($breadcrumbs) > 1)
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    @foreach($breadcrumbs as $breadcrumb)
                        @if(!$loop->last)
                            <li>
                                <a href="{{ $breadcrumb['url'] }}" class="text-gray-500 hover:text-gray-700 text-sm">
                                    {{ $breadcrumb['name'] }}
                                </a>
                            </li>
                            <li>
                                <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                            </li>
                        @else
                            <li>
                                <span class="text-gray-900 text-sm font-medium">{{ $breadcrumb['name'] }}</span>
                            </li>
                        @endif
                    @endforeach
                </ol>
            </nav>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Logo & Description -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center mb-4">
                        <div class="h-8 w-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-star text-white"></i>
                        </div>
                        <span class="font-bold text-xl">Smartest Reviews</span>
                    </div>
                    <p class="text-gray-400 mb-4">
                        Expert product reviews, detailed comparisons, and unbiased recommendations to help you make informed decisions.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white transition-colors">Home</a></li>
                        <li><a href="{{ route('posts.index') }}" class="text-gray-400 hover:text-white transition-colors">Latest Posts</a></li>
                        <li><a href="{{ route('how-we-rank') }}" class="text-gray-400 hover:text-white transition-colors">How We Rank</a></li>
                        <li><a href="{{ route('privacy') }}" class="text-gray-400 hover:text-white transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>

                <!-- Categories -->
                <div>
                    <h3 class="font-semibold mb-4">Categories</h3>
                    <ul class="space-y-2">
                        @foreach(\App\Models\Category::active()->take(5)->get() as $category)
                            <li>
                                <a href="{{ route('categories.show', $category->slug) }}" 
                                   class="text-gray-400 hover:text-white transition-colors">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p class="text-gray-400">
                    © {{ date('Y') }} Smartest Reviews. All rights reserved. | 
                    <a href="{{ route('privacy') }}" class="hover:text-white transition-colors">Privacy Policy</a> | 
                    <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                </p>
            </div>
        </div>
    </footer>

    <!-- Alpine.js Data -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('app', () => ({
                mobileMenuOpen: false,
                
                init() {
                    // Initialize any global functionality
                }
            }));
        });
    </script>
</body>
</html>