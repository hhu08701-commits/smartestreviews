<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Smart Reviews</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-[#2c3e50] text-white">
            <div class="p-6 border-b border-[#34495e]">
                <h2 class="text-xl font-bold" style="font-family: 'Montserrat', sans-serif;">
                    <span style="color: #fff;">smartest</span>
                    <span style="color: #f8c2eb;"> reviews</span>
                </h2>
                <p class="text-xs text-gray-400 mt-1">Admin Panel</p>
            </div>
            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-6 py-3 text-gray-300 transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-[#f8c2eb] text-[#000] font-semibold' : 'hover:bg-[#34495e] hover:text-white' }}"
                   style="font-family: 'Montserrat', sans-serif;">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.posts.index') }}" 
                   class="flex items-center px-6 py-3 text-gray-300 transition-colors duration-200 {{ request()->routeIs('admin.posts.*') ? 'bg-[#f8c2eb] text-[#000] font-semibold' : 'hover:bg-[#34495e] hover:text-white' }}"
                   style="font-family: 'Montserrat', sans-serif;">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Posts
                </a>
                        <a href="{{ route('admin.affiliate-links.index') }}" 
                           class="flex items-center px-6 py-3 text-gray-300 transition-colors duration-200 {{ request()->routeIs('admin.affiliate-links.*') ? 'bg-[#f8c2eb] text-[#000] font-semibold' : 'hover:bg-[#34495e] hover:text-white' }}"
                           style="font-family: 'Montserrat', sans-serif;">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                            Affiliate Links
                        </a>
                        <a href="{{ route('admin.product-showcases.index') }}" 
                           class="flex items-center px-6 py-3 text-gray-300 transition-colors duration-200 {{ request()->routeIs('admin.product-showcases.*') ? 'bg-[#f8c2eb] text-[#000] font-semibold' : 'hover:bg-[#34495e] hover:text-white' }}"
                           style="font-family: 'Montserrat', sans-serif;">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Product Showcases
                        </a>
        <a href="{{ route('admin.categories.index') }}" 
           class="flex items-center px-6 py-3 text-gray-300 transition-colors duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-[#f8c2eb] text-[#000] font-semibold' : 'hover:bg-[#34495e] hover:text-white' }}"
           style="font-family: 'Montserrat', sans-serif;">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
            Categories
        </a>
        <a href="{{ route('admin.slideshow.index') }}" 
           class="flex items-center px-6 py-3 text-gray-300 transition-colors duration-200 {{ request()->routeIs('admin.slideshow.*') ? 'bg-[#f8c2eb] text-[#000] font-semibold' : 'hover:bg-[#34495e] hover:text-white' }}"
           style="font-family: 'Montserrat', sans-serif;">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Slideshow
        </a>
        <a href="{{ route('admin.hot-products.index') }}" 
           class="flex items-center px-6 py-3 text-gray-300 transition-colors duration-200 {{ request()->routeIs('admin.hot-products.*') ? 'bg-[#f8c2eb] text-[#000] font-semibold' : 'hover:bg-[#34495e] hover:text-white' }}"
           style="font-family: 'Montserrat', sans-serif;">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
            </svg>
            Hot Products
        </a>
        <a href="{{ route('admin.sources.index') }}" 
           class="flex items-center px-6 py-3 text-gray-300 transition-colors duration-200 {{ request()->routeIs('admin.sources.*') ? 'bg-[#f8c2eb] text-[#000] font-semibold' : 'hover:bg-[#34495e] hover:text-white' }}"
           style="font-family: 'Montserrat', sans-serif;">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            Sources
        </a>
        <a href="{{ route('admin.tags.index') }}" 
           class="flex items-center px-6 py-3 text-gray-300 transition-colors duration-200 {{ request()->routeIs('admin.tags.*') ? 'bg-[#f8c2eb] text-[#000] font-semibold' : 'hover:bg-[#34495e] hover:text-white' }}"
           style="font-family: 'Montserrat', sans-serif;">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
            Tags
        </a>
        <a href="{{ route('admin.breaking-news.index') }}" 
           class="flex items-center px-6 py-3 text-gray-300 transition-colors duration-200 {{ request()->routeIs('admin.breaking-news.*') ? 'bg-[#f8c2eb] text-[#000] font-semibold' : 'hover:bg-[#34495e] hover:text-white' }}"
           style="font-family: 'Montserrat', sans-serif;">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            Breaking News
        </a>
                <a href="{{ route('home') }}" 
                   class="flex items-center px-6 py-3 text-gray-300 transition-colors duration-200 hover:bg-[#34495e] hover:text-white"
                   style="font-family: 'Montserrat', sans-serif;"
                   target="_blank">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    View Site
                </a>
            </nav>
        </div>

        <!-- Main content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-semibold text-gray-900">@yield('title', 'Admin Panel')</h1>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-600">Welcome, {{ Auth::user()->name }}</span>
                            <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-red-600 hover:text-red-800">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 p-6">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>
