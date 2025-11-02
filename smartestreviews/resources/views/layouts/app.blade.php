<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Smartest Reviews - Expert Product Reviews & Comparisons')</title>
    <meta name="description" content="@yield('description', 'Discover the best products with our expert reviews, detailed comparisons, and unbiased recommendations. Find your perfect match today.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap" rel="stylesheet" />
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        * {
            box-sizing: border-box;
        }
        
        body {
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', sans-serif;
            background-color: #ffffff;
            color: #333;
        }
        
        .container-wrapper {
            max-width: 1140px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        
        /* Responsive Styles */
        @media (max-width: 992px) {
            .container-wrapper {
                padding: 0 10px;
            }
            
            .menu-desktop {
                display: none !important;
            }
            
            .mobile-menu-toggle {
                display: block !important;
            }
        }
        
        @media (max-width: 768px) {
            .site-title {
                font-size: 28px !important;
            }
            
            .bottom-header {
                height: auto !important;
                padding: 10px 0 !important;
            }
            
            .main-navigation {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <!-- Mid Header with Logo -->
        <div class="mid-header" style="background: #fff; padding: 20px 0; border-bottom: 1px solid #e0e0e0;">
            <div class="container-wrapper">
                <div class="mid-bar-flex" style="display: flex; align-items: center; justify-content: center;">
                    <div class="logo" style="text-align: center;">
                        <div class="site-branding">
                            <a href="{{ route('home') }}" style="text-decoration: none; display: inline-block;">
                                <h1 class="site-title" style="margin: 0; font-size: 36px; font-weight: 700; font-family: 'Montserrat', sans-serif; color: #000; line-height: 1.2;">
                                    <span style="color: #000 !important; display: inline-block; position: relative; z-index: 1;">smartest</span>
                                    <span style="color: #000 !important; position: relative; z-index: 1; display: inline-block;">
                                        reviews
                                        <span style="position: absolute; bottom: -5px; left: 0; right: 0; height: 3px; background: linear-gradient(to right, #9b51e0, #2670ff); z-index: 0;"></span>
                                    </span>
                                </h1>
                                <p style="margin: 5px 0 0 0; font-size: 14px; color: #666 !important; font-weight: 400; font-style: italic; position: relative; z-index: 1;">
                                    your favorite products, reviewed.
                                </p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Navigation Bar -->
        <div id="main-navigation-bar" class="bottom-header" style="background-color: #6db2e2;">
            <div class="container-wrapper">
                <div style="display: flex; justify-between; align-items: center; height: 56px;">
                    <!-- Navigation Menu -->
                    <nav class="main-navigation" style="flex: 1;">
                        <div class="menu main-menu menu-desktop" style="display: flex; align-items: center; gap: 0;">
                            <a href="{{ route('home') }}" 
                               style="color: #fff; text-decoration: none; padding: 10px 20px; font-size: 14px; font-weight: 700; text-transform: uppercase; font-family: 'Montserrat', sans-serif; {{ request()->routeIs('home') ? 'background-color: rgba(255,255,255,0.2);' : '' }}">
                                Home
                            </a>
                            <a href="{{ route('privacy') }}" 
                               style="color: #fff; text-decoration: none; padding: 10px 20px; font-size: 14px; font-weight: 700; text-transform: uppercase; font-family: 'Montserrat', sans-serif; {{ request()->routeIs('privacy') ? 'background-color: rgba(255,255,255,0.2);' : '' }}">
                                Privacy Policy
                            </a>
                            <a href="{{ route('posts.index') }}" 
                               style="color: #fff; text-decoration: none; padding: 10px 20px; font-size: 14px; font-weight: 700; text-transform: uppercase; font-family: 'Montserrat', sans-serif; {{ request()->routeIs('posts.*') ? 'background-color: rgba(255,255,255,0.2);' : '' }}">
                                Latest Posts
                            </a>
                            <a href="{{ route('how-we-rank') }}" 
                               style="color: #fff; text-decoration: none; padding: 10px 20px; font-size: 14px; font-weight: 700; text-transform: uppercase; font-family: 'Montserrat', sans-serif; {{ request()->routeIs('how-we-rank') ? 'background-color: rgba(255,255,255,0.2);' : '' }}">
                                How We Rank
                            </a>
                        </div>
                    </nav>

                    <!-- Right Side Actions -->
                    <div class="search-watch" style="display: flex; align-items: center; gap: 15px;">
                        <!-- Search -->
                        <div class="af-search-wrap">
                            <a href="#" title="Search" class="search-icon" style="color: #fff; font-size: 18px; text-decoration: none;">
                                <i class="fa fa-search"></i>
                            </a>
                        </div>
                        
                        <!-- Subscribe Button -->
                        <div class="custom-menu-link">
                            <a href="#" style="background: #ffd700; color: #000; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-weight: 700; font-size: 13px; text-transform: uppercase; font-family: 'Montserrat', sans-serif;">
                                <i class="fas fa-bell" style="margin-right: 5px;"></i>
                                Subscribe
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Breadcrumbs -->
    @if(isset($breadcrumbs) && count($breadcrumbs) > 1)
    <div style="background: #fff; border-bottom: 1px solid #e0e0e0;">
        <div class="container-wrapper" style="padding: 10px 15px;">
            <nav style="font-size: 13px;">
                @foreach($breadcrumbs as $breadcrumb)
                    @if(!$loop->last)
                        <a href="{{ $breadcrumb['url'] }}" style="color: #666; text-decoration: none;">
                            {{ $breadcrumb['name'] }}
                        </a>
                        <span style="margin: 0 8px; color: #ccc;">/</span>
                    @else
                        <span style="color: #333; font-weight: 600;">{{ $breadcrumb['name'] }}</span>
                    @endif
                @endforeach
            </nav>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- YOU MAY HAVE MISSED Section - Available on all pages -->
    <x-missed-posts />

    <!-- Footer -->
    <footer class="site-footer aft-footer-sidebar-col-0" style="background: #2c3e50; color: #fff; padding: 20px 0;">
        <div class="site-info">
            <div class="container-wrapper">
                <div class="af-container-row" style="text-align: center; font-size: 14px; color: #bdc3c7; font-family: 'Montserrat', sans-serif;">
                    <div class="col-1 color-pad">
                        Copyright © {{ date('Y') }} All rights reserved.
                        <span class="sep" style="margin: 0 10px;">|</span>
                        <a href="https://afthemes.com/products/chromenews/" target="_blank" style="color: #bdc3c7; text-decoration: none;">ChromeNews</a> by AF themes.
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <a id="scroll-up" 
       onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
       style="position: fixed; bottom: 30px; right: 30px; width: 50px; height: 50px; background: #f8c2eb; color: #000; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15); opacity: 0; pointer-events: none; transition: opacity 0.3s; z-index: 1000;">
        <i class="fas fa-arrow-up"></i>
    </a>

    <script>
        // Scroll to top button
        window.addEventListener('scroll', function() {
            const scrollBtn = document.getElementById('scroll-up');
            if (window.scrollY > 300) {
                scrollBtn.style.opacity = '1';
                scrollBtn.style.pointerEvents = 'auto';
            } else {
                scrollBtn.style.opacity = '0';
                scrollBtn.style.pointerEvents = 'none';
            }
        });
    </script>
</body>
</html>
