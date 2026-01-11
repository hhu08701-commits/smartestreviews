<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Smartest Reviews - Expert Product Reviews & Comparisons')</title>
    <meta name="description" content="@yield('description', 'Discover the best products with our expert reviews, detailed comparisons, and unbiased recommendations. Find your perfect match today.')">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

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
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 25%, #f8fafc 50%, #f1f5f9 75%, #f0f9ff 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            color: #1e293b;
            min-height: 100vh;
            position: relative;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(14, 165, 233, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.06) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(245, 158, 11, 0.05) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .container-wrapper {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px;
            position: relative;
            z-index: 1;
        }
        
        main {
            position: relative;
            z-index: 1;
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
        <div class="mid-header" style="background: linear-gradient(135deg, #ffffff 0%, #f0f9ff 100%); padding: 25px 0; border-bottom: 2px solid rgba(14, 165, 233, 0.2); box-shadow: 0 2px 8px rgba(14, 165, 233, 0.1);">
            <div class="container-wrapper">
                <div class="mid-bar-flex" style="display: flex; align-items: center; justify-content: center;">
                    <div class="logo" style="text-align: center;">
                        <div class="site-branding">
                            <a href="{{ route('home') }}" style="text-decoration: none; display: inline-block;">
                                <h1 class="site-title" style="margin: 0; font-size: 42px; font-weight: 800; font-family: 'Montserrat', sans-serif; line-height: 1.2; letter-spacing: -0.5px;">
                                    <span style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; display: inline-block; position: relative; z-index: 1;">smartest</span>
                                    <span style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; position: relative; z-index: 1; display: inline-block; margin-left: 8px;">
                                        reviews
                                        <span style="position: absolute; bottom: -8px; left: 0; right: 0; height: 4px; background: linear-gradient(to right, #0ea5e9, #8b5cf6, #f59e0b); border-radius: 2px; z-index: 0; opacity: 0.8;"></span>
                                    </span>
                                </h1>
                                <p style="margin: 8px 0 0 0; font-size: 15px; background: linear-gradient(135deg, #64748b 0%, #475569 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-weight: 500; font-style: italic; position: relative; z-index: 1;">
                                    your favorite products, reviewed.
                                </p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Navigation Bar -->
        <div id="main-navigation-bar" class="bottom-header" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); box-shadow: 0 2px 8px rgba(14, 165, 233, 0.2);">
            <div class="container-wrapper">
                <div style="display: flex; justify-between; align-items: center; height: 60px;">
                    <!-- Navigation Menu -->
                    <nav class="main-navigation" style="flex: 1;">
                        <div class="menu main-menu menu-desktop" style="display: flex; align-items: center; gap: 0;">
                            <a href="{{ route('home') }}" 
                               style="color: #fff; text-decoration: none; padding: 12px 24px; font-size: 14px; font-weight: 700; text-transform: uppercase; font-family: 'Montserrat', sans-serif; transition: all 0.3s ease; border-radius: 6px; margin: 0 4px; {{ request()->routeIs('home') ? 'background: linear-gradient(135deg, rgba(255,255,255,0.25) 0%, rgba(255,255,255,0.15) 100%); box-shadow: 0 2px 8px rgba(0,0,0,0.1);' : '' }}"
                               onmouseover="if(!this.style.background.includes('rgba(255,255,255')) this.style.background='linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0.1) 100%)'; this.style.transform='translateY(-2px)'"
                               onmouseout="if(!{{ request()->routeIs('home') ? 'true' : 'false' }}) {this.style.background=''; this.style.transform=''}">
                                Home
                            </a>
                            <a href="{{ route('privacy') }}" 
                               style="color: #fff; text-decoration: none; padding: 12px 24px; font-size: 14px; font-weight: 700; text-transform: uppercase; font-family: 'Montserrat', sans-serif; transition: all 0.3s ease; border-radius: 6px; margin: 0 4px; {{ request()->routeIs('privacy') ? 'background: linear-gradient(135deg, rgba(255,255,255,0.25) 0%, rgba(255,255,255,0.15) 100%); box-shadow: 0 2px 8px rgba(0,0,0,0.1);' : '' }}"
                               onmouseover="if(!this.style.background.includes('rgba(255,255,255')) this.style.background='linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0.1) 100%)'; this.style.transform='translateY(-2px)'"
                               onmouseout="if(!{{ request()->routeIs('privacy') ? 'true' : 'false' }}) {this.style.background=''; this.style.transform=''}">
                                Privacy Policy
                            </a>
                            <a href="{{ route('posts.index') }}" 
                               style="color: #fff; text-decoration: none; padding: 12px 24px; font-size: 14px; font-weight: 700; text-transform: uppercase; font-family: 'Montserrat', sans-serif; transition: all 0.3s ease; border-radius: 6px; margin: 0 4px; {{ request()->routeIs('posts.*') ? 'background: linear-gradient(135deg, rgba(255,255,255,0.25) 0%, rgba(255,255,255,0.15) 100%); box-shadow: 0 2px 8px rgba(0,0,0,0.1);' : '' }}"
                               onmouseover="if(!this.style.background.includes('rgba(255,255,255')) this.style.background='linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0.1) 100%)'; this.style.transform='translateY(-2px)'"
                               onmouseout="if(!{{ request()->routeIs('posts.*') ? 'true' : 'false' }}) {this.style.background=''; this.style.transform=''}">
                                Latest Posts
                            </a>
                            <a href="{{ route('how-we-rank') }}" 
                               style="color: #fff; text-decoration: none; padding: 12px 24px; font-size: 14px; font-weight: 700; text-transform: uppercase; font-family: 'Montserrat', sans-serif; transition: all 0.3s ease; border-radius: 6px; margin: 0 4px; {{ request()->routeIs('how-we-rank') ? 'background: linear-gradient(135deg, rgba(255,255,255,0.25) 0%, rgba(255,255,255,0.15) 100%); box-shadow: 0 2px 8px rgba(0,0,0,0.1);' : '' }}"
                               onmouseover="if(!this.style.background.includes('rgba(255,255,255')) this.style.background='linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0.1) 100%)'; this.style.transform='translateY(-2px)'"
                               onmouseout="if(!{{ request()->routeIs('how-we-rank') ? 'true' : 'false' }}) {this.style.background=''; this.style.transform=''}">
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

    <!-- Snow Effect -->
    <x-snow-effect />

    <!-- Scroll to Top Button -->
    <a id="scroll-up" 
       onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
       style="position: fixed; bottom: 30px; right: 30px; width: 56px; height: 56px; background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; box-shadow: 0 4px 16px rgba(14, 165, 233, 0.4); opacity: 0; pointer-events: none; transition: all 0.3s ease; z-index: 1000; border: 2px solid rgba(255, 255, 255, 0.3);"
       onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 6px 20px rgba(14, 165, 233, 0.5)'"
       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 16px rgba(14, 165, 233, 0.4)'">
        <i class="fas fa-arrow-up" style="font-size: 18px;"></i>
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
