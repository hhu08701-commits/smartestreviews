<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Models\ProductShowcase;
use App\Models\BreakingNews;
use App\Models\SlideshowSlide;
use App\Models\HotProduct;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DownloadAndStoreImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:download-and-store 
                            {--force : Force re-download even if image already exists}
                            {--limit= : Limit number of images to process per model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download all images from URLs and store them locally in database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting image download and storage process...');
        $this->newLine();

        $force = $this->option('force');
        $limit = $this->option('limit') ? (int)$this->option('limit') : null;

        // Process Posts
        $this->info('📝 Processing Posts...');
        $this->processPosts($force, $limit);
        $this->newLine();

        // Process ProductShowcases
        $this->info('🛍️ Processing Product Showcases...');
        $this->processProductShowcases($force, $limit);
        $this->newLine();

        // Process BreakingNews
        $this->info('📰 Processing Breaking News...');
        $this->processBreakingNews($force, $limit);
        $this->newLine();

        // Process SlideshowSlides
        $this->info('🖼️ Processing Slideshow Slides...');
        $this->processSlideshowSlides($force, $limit);
        $this->newLine();

        // Process HotProducts
        $this->info('🔥 Processing Hot Products...');
        $this->processHotProducts($force, $limit);
        $this->newLine();

        $this->info('✅ Image download and storage process completed!');
    }

    /**
     * Process Posts
     */
    protected function processPosts($force = false, $limit = null)
    {
        $query = Post::whereNotNull('featured_image')
            ->where('featured_image', 'like', 'http%');

        if ($limit) {
            $query->limit($limit);
        }

        $posts = $query->get();
        $total = $posts->count();
        $processed = 0;
        $skipped = 0;
        $errors = 0;

        $this->withProgressBar($posts, function ($post) use ($force, &$processed, &$skipped, &$errors) {
            // Skip if already local and file exists (unless force)
            if (!$force && !filter_var($post->featured_image, FILTER_VALIDATE_URL) && 
                file_exists(public_path($post->featured_image))) {
                $skipped++;
                return;
            }

            $imageUrl = $post->featured_image;
            
            // Skip if not a URL
            if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                $skipped++;
                return;
            }

            $result = $this->downloadImage($imageUrl, 'posts', $post->id . '_featured');
            
            if ($result) {
                $localPath = '/uploads/posts/' . $result['filename'];
                $post->update([
                    'image_path' => $result['filename'],
                    'image_filename' => $result['filename'],
                    'image_original_name' => $result['original_name'],
                    'featured_image' => $localPath, // Update to local path
                ]);
                $processed++;
            } else {
                $errors++;
            }
        });

        $this->newLine();
        $this->line("  Processed: {$processed}, Skipped: {$skipped}, Errors: {$errors}");
    }

    /**
     * Process ProductShowcases
     */
    protected function processProductShowcases($force = false, $limit = null)
    {
        $query = ProductShowcase::where(function($q) {
                $q->whereNotNull('image_url')
                  ->where('image_url', 'like', 'http%');
            });

        if ($limit) {
            $query->limit($limit);
        }

        $showcases = $query->get();
        $total = $showcases->count();
        $processed = 0;
        $skipped = 0;
        $errors = 0;

        $this->withProgressBar($showcases, function ($showcase) use ($force, &$processed, &$skipped, &$errors) {
            // Use product_image_url first, fallback to image_url
            $imageUrl = $showcase->product_image_url ?? $showcase->image_url;
            
            // Skip if not a URL
            if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                $skipped++;
                return;
            }

            // Skip if already local and file exists (unless force)
            if (!$force && !filter_var($imageUrl, FILTER_VALIDATE_URL) && 
                file_exists(public_path($imageUrl))) {
                $skipped++;
                return;
            }

            $result = $this->downloadImage($imageUrl, 'products', $showcase->id . '_product');
            
            if ($result) {
                $localPath = '/uploads/products/' . $result['filename'];
                $showcase->update([
                    'image_url' => $localPath, // Update to local path
                    'image_path' => $result['filename'],
                    'image_filename' => $result['filename'],
                    'image_original_name' => $result['original_name'],
                ]);
                $processed++;
            } else {
                $errors++;
            }
        });

        $this->newLine();
        $this->line("  Processed: {$processed}, Skipped: {$skipped}, Errors: {$errors}");
    }

    /**
     * Process BreakingNews
     */
    protected function processBreakingNews($force = false, $limit = null)
    {
        // First, check if breaking_news table has image_path column
        // If not, we'll need to add it via migration
        $query = BreakingNews::whereNotNull('image_url');

        if ($limit) {
            $query->limit($limit);
        }

        $breakingNews = $query->get();
        $processed = 0;
        $skipped = 0;
        $errors = 0;

        $this->withProgressBar($breakingNews, function ($item) use ($force, &$processed, &$skipped, &$errors) {
            $imageUrl = $item->image_url;
            
            // Skip if not a URL
            if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                $skipped++;
                return;
            }

            $result = $this->downloadImage($imageUrl, 'breaking-news', $item->id . '_breaking');
            
            if ($result) {
                // Update image_url to local path, keep original as backup
                $item->update([
                    'image_url' => '/uploads/breaking-news/' . $result['filename'],
                ]);
                $processed++;
            } else {
                $errors++;
            }
        });

        $this->newLine();
        $this->line("  Processed: {$processed}, Skipped: {$skipped}, Errors: {$errors}");
    }

    /**
     * Process SlideshowSlides
     */
    protected function processSlideshowSlides($force = false, $limit = null)
    {
        $query = SlideshowSlide::whereNotNull('image')
            ->where('image', 'like', 'http%');

        if ($limit) {
            $query->limit($limit);
        }

        $slides = $query->get();
        $processed = 0;
        $skipped = 0;
        $errors = 0;

        $this->withProgressBar($slides, function ($slide) use ($force, &$processed, &$skipped, &$errors) {
            $imageUrl = $slide->image;
            
            // Skip if not a URL
            if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                $skipped++;
                return;
            }

            $result = $this->downloadImage($imageUrl, 'slideshow', $slide->id . '_slide');
            
            if ($result) {
                $slide->update([
                    'image' => '/uploads/slideshow/' . $result['filename'],
                ]);
                $processed++;
            } else {
                $errors++;
            }
        });

        $this->newLine();
        $this->line("  Processed: {$processed}, Skipped: {$skipped}, Errors: {$errors}");
    }

    /**
     * Process HotProducts
     */
    protected function processHotProducts($force = false, $limit = null)
    {
        $query = HotProduct::whereNotNull('image')
            ->where('image', 'like', 'http%');

        if ($limit) {
            $query->limit($limit);
        }

        $products = $query->get();
        $processed = 0;
        $skipped = 0;
        $errors = 0;

        $this->withProgressBar($products, function ($product) use ($force, &$processed, &$skipped, &$errors) {
            $imageUrl = $product->image;
            
            // Skip if not a URL
            if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                $skipped++;
                return;
            }

            $result = $this->downloadImage($imageUrl, 'hot-products', $product->id . '_hot');
            
            if ($result) {
                $product->update([
                    'image' => '/uploads/hot-products/' . $result['filename'],
                ]);
                $processed++;
            } else {
                $errors++;
            }
        });

        $this->newLine();
        $this->line("  Processed: {$processed}, Skipped: {$skipped}, Errors: {$errors}");
    }

    /**
     * Download image from URL and save to local storage
     */
    protected function downloadImage($url, $folder, $prefix = '')
    {
        try {
            // Skip localhost/127.0.0.1 URLs - these are already local
            $host = parse_url($url, PHP_URL_HOST);
            if (in_array($host, ['localhost', '127.0.0.1', '::1']) || str_starts_with($host, '127.')) {
                // Try to extract local path from URL
                $path = parse_url($url, PHP_URL_PATH);
                if ($path && file_exists(public_path($path))) {
                    return [
                        'filename' => basename($path),
                        'original_name' => basename($path),
                        'path' => public_path($path),
                    ];
                }
                return false;
            }

            // Create upload directory if it doesn't exist
            $uploadPath = public_path("uploads/{$folder}");
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Get image content
            $response = Http::timeout(30)->get($url);
            
            if (!$response->successful()) {
                return false;
            }

            // Get file extension from URL or Content-Type
            $contentType = $response->header('Content-Type');
            $extension = 'jpg'; // default
            
            if (preg_match('/image\/(\w+)/', $contentType, $matches)) {
                $extension = $matches[1];
            } elseif (preg_match('/\.(\w+)(?:\?|$)/', $url, $matches)) {
                $extension = $matches[1];
            }

            // Clean extension
            $extension = strtolower($extension);
            if ($extension === 'jpeg') {
                $extension = 'jpg';
            }

            // Generate filename
            $filename = $prefix . '_' . time() . '_' . Str::random(10) . '.' . $extension;
            $filePath = $uploadPath . '/' . $filename;

            // Save file
            file_put_contents($filePath, $response->body());

            // Verify file was saved
            if (file_exists($filePath) && filesize($filePath) > 0) {
                return [
                    'filename' => $filename,
                    'original_name' => basename(parse_url($url, PHP_URL_PATH)),
                    'path' => $filePath,
                ];
            }

            return false;
        } catch (\Exception $e) {
            $this->error("Error downloading {$url}: " . $e->getMessage());
            return false;
        }
    }
}
