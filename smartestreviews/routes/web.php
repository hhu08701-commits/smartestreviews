<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\AffiliateLinkController as AdminAffiliateLinkController;
use App\Http\Controllers\Admin\ProductShowcaseController as AdminProductShowcaseController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\SlideshowController as AdminSlideshowController;
use App\Http\Controllers\Admin\HotProductController as AdminHotProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\SourceController as AdminSourceController;
use Illuminate\Support\Facades\Route;

// Admin authentication routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

// Admin protected routes
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/test', function () {
        return 'Admin test works!';
    })->name('test');
    
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Posts management
    Route::resource('posts', AdminPostController::class);
    
            // Affiliate links management
            Route::resource('affiliate-links', AdminAffiliateLinkController::class);
            
            // Product showcases management
            Route::resource('product-showcases', AdminProductShowcaseController::class);
            
            // Categories management
            Route::resource('categories', AdminCategoryController::class);
            
            // Slideshow management
            Route::resource('slideshow', AdminSlideshowController::class);
            
            // Hot products management
            Route::resource('hot-products', AdminHotProductController::class);
            Route::patch('hot-products/{hotProduct}/update-sort', [AdminHotProductController::class, 'updateSort'])->name('hot-products.update-sort');
            
            // Sources management
            Route::resource('sources', AdminSourceController::class);
        });

// Client-side routes with page view tracking
Route::middleware('track.pageview')->group(function () {
    // Home page
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Static pages
    Route::get('/privacy', function () {
        $sources = \App\Models\Source::active()->ordered()->get();
        return view('pages.privacy', compact('sources'));
    })->name('privacy');

    Route::get('/terms', function () {
        return view('pages.terms');
    })->name('terms');

    Route::get('/about', function () {
        return view('pages.about');
    })->name('about');

    Route::get('/contact', function () {
        return view('pages.contact');
    })->name('contact');

    Route::get('/how-we-rank', function () {
        return view('pages.how-we-rank');
    })->name('how-we-rank');

    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

    // Category pages
    Route::get('/category/{category}', [CategoryController::class, 'show'])->name('categories.show');

    // Tag pages
    Route::get('/tag/{tag}', [CategoryController::class, 'showTag'])->name('tags.show');

    // Search
    Route::get('/search', [SearchController::class, 'index'])->name('search');

    // Hot Products
    Route::get('/hot-products', [App\Http\Controllers\HotProductController::class, 'index'])->name('hot-products.index');

    Route::get('/affiliate-disclosure', function () {
        return view('pages.affiliate-disclosure');
    })->name('affiliate-disclosure');

    // Post pages - must be last to avoid conflicts
    Route::get('/{year}/{month}/{slug}', [PostController::class, 'show'])
        ->where('year', '\d{4}')
        ->where('month', '\d{2}')
        ->name('posts.show');
});

// Affiliate redirect (no tracking, handled separately)
Route::get('/go/{slug}', [AffiliateController::class, 'redirect'])->name('affiliate.redirect');