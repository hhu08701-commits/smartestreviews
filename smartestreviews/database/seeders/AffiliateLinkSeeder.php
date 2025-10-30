<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AffiliateLink;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Support\Str;

class AffiliateLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some posts and products for association
        $posts = Post::take(3)->get();
        $products = Product::take(3)->get();

        $affiliateLinks = [
            [
                'label' => 'Best Baby Monitor - Amazon',
                'url' => 'https://amazon.com/best-baby-monitor',
                'merchant' => 'Amazon',
                'rel' => 'sponsored nofollow',
                'post_id' => $posts->first()?->id,
                'product_id' => $products->first()?->id,
                'enabled' => true,
                'utm_params' => 'utm_source=smartestreviews&utm_medium=affiliate&utm_campaign=baby_monitor_review',
            ],
            [
                'label' => 'Top Rated Vacuum Cleaner - Best Buy',
                'url' => 'https://bestbuy.com/top-vacuum-cleaner',
                'merchant' => 'Best Buy',
                'rel' => 'sponsored nofollow',
                'post_id' => $posts->skip(1)->first()?->id,
                'product_id' => $products->skip(1)->first()?->id,
                'enabled' => true,
                'utm_params' => 'utm_source=smartestreviews&utm_medium=affiliate&utm_campaign=vacuum_review',
            ],
            [
                'label' => 'Wireless Headphones - Target',
                'url' => 'https://target.com/wireless-headphones',
                'merchant' => 'Target',
                'rel' => 'sponsored nofollow',
                'post_id' => $posts->skip(2)->first()?->id,
                'product_id' => $products->skip(2)->first()?->id,
                'enabled' => true,
                'utm_params' => 'utm_source=smartestreviews&utm_medium=affiliate&utm_campaign=headphones_review',
            ],
            [
                'label' => 'Kitchen Blender - Walmart',
                'url' => 'https://walmart.com/kitchen-blender',
                'merchant' => 'Walmart',
                'rel' => 'sponsored nofollow',
                'enabled' => true,
                'utm_params' => 'utm_source=smartestreviews&utm_medium=affiliate&utm_campaign=blender_review',
            ],
            [
                'label' => 'Smart Watch - Apple Store',
                'url' => 'https://apple.com/smart-watch',
                'merchant' => 'Apple',
                'rel' => 'sponsored nofollow',
                'enabled' => false, // Disabled for testing
                'utm_params' => 'utm_source=smartestreviews&utm_medium=affiliate&utm_campaign=smartwatch_review',
            ],
        ];

        foreach ($affiliateLinks as $linkData) {
            $linkData['slug'] = Str::slug($linkData['label']);
            AffiliateLink::create($linkData);
        }
    }
}