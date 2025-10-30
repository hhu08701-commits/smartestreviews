<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create a user
        $user = User::firstOrCreate(
            ['email' => 'admin@smartestreviews.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
            ]
        );

        // Get categories
        $beautyCategory = Category::where('slug', 'beauty')->first();
        $electronicsCategory = Category::where('slug', 'electronics')->first();
        $homeDecorCategory = Category::where('slug', 'home-decor')->first();
        $featuredCategory = Category::where('slug', 'featured-reviews')->first();

        $posts = [
            [
                'title' => 'Top 5 Hydroxyapatite Toothpastes 2025',
                'slug' => 'top-5-hydroxyapatite-toothpastes-2025',
                'excerpt' => 'Discover the best hydroxyapatite toothpastes for remineralizing your teeth and improving oral health.',
                'content' => '<p>Hydroxyapatite toothpaste has gained popularity as a natural alternative to fluoride-based products. This mineral compound, which makes up 97% of tooth enamel, helps remineralize teeth and prevent cavities.</p><p>After testing over 20 different brands, we\'ve compiled our top 5 picks based on effectiveness, ingredients, and user reviews.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1607619056574-7b8d3ee536b2?w=800&h=400&fit=crop',
                'featured_image_alt' => 'Top hydroxyapatite toothpastes for dental health',
                'featured_image_caption' => 'Best hydroxyapatite toothpastes for remineralizing teeth',
                'post_type' => 'list',
                'status' => 'published',
                'published_at' => now()->subDays(1),
                'rating' => 4.5,
                'meta_title' => 'Best Hydroxyapatite Toothpastes 2025 - Expert Reviews',
                'meta_description' => 'Find the best hydroxyapatite toothpastes for remineralizing teeth. Expert reviews and comparisons of top brands.',
                'views_count' => 1250,
                'clicks_count' => 45,
            ],
            [
                'title' => 'Probiotics 2025: Complete Guide to Gut Health',
                'slug' => 'probiotics-2025-complete-guide-gut-health',
                'excerpt' => 'Everything you need to know about probiotics, including the best strains, dosages, and products for optimal gut health.',
                'content' => '<p>Probiotics have become essential for maintaining a healthy gut microbiome. With so many options available, choosing the right probiotic can be overwhelming.</p><p>This comprehensive guide covers everything from understanding different strains to finding the best products for your specific needs.</p>',
                'post_type' => 'review',
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'product_name' => 'Garden of Life Probiotics',
                'brand' => 'Garden of Life',
                'rating' => 4.8,
                'pros' => ['High CFU count', 'Multiple strains', 'Shelf-stable', 'Non-GMO'],
                'cons' => ['Expensive', 'Large capsules'],
                'price_text' => '$39.99',
                'meta_title' => 'Best Probiotics 2025 - Complete Gut Health Guide',
                'meta_description' => 'Expert guide to probiotics: best strains, dosages, and top-rated products for gut health in 2025.',
                'views_count' => 2100,
                'clicks_count' => 78,
            ],
            [
                'title' => 'Best Air Purifiers for Allergies 2025',
                'slug' => 'best-air-purifiers-allergies-2025',
                'excerpt' => 'Top-rated air purifiers that effectively remove allergens, dust, and pollutants from your home.',
                'content' => '<p>If you suffer from allergies, asthma, or simply want cleaner air in your home, an air purifier can make a significant difference.</p><p>We\'ve tested the latest models to find the most effective air purifiers for allergy relief.</p>',
                'post_type' => 'list',
                'status' => 'published',
                'published_at' => now()->subDays(3),
                'rating' => 4.3,
                'meta_title' => 'Best Air Purifiers for Allergies 2025 - Expert Reviews',
                'meta_description' => 'Find the best air purifiers for allergy relief. Expert reviews of top-rated models for cleaner air.',
                'views_count' => 1850,
                'clicks_count' => 62,
            ],
            [
                'title' => 'How to Choose the Right Skincare Routine',
                'slug' => 'how-to-choose-right-skincare-routine',
                'excerpt' => 'A beginner\'s guide to building an effective skincare routine based on your skin type and concerns.',
                'content' => '<p>Building the perfect skincare routine doesn\'t have to be complicated. With the right products and techniques, you can achieve healthy, glowing skin.</p><p>This guide will help you understand your skin type and choose the right products for your needs.</p>',
                'post_type' => 'how-to',
                'status' => 'published',
                'published_at' => now()->subDays(4),
                'meta_title' => 'How to Choose the Right Skincare Routine - Complete Guide',
                'meta_description' => 'Learn how to build the perfect skincare routine for your skin type. Expert tips and product recommendations.',
                'views_count' => 3200,
                'clicks_count' => 95,
            ],
            [
                'title' => 'Top 10 Smart Home Devices for 2025',
                'slug' => 'top-10-smart-home-devices-2025',
                'excerpt' => 'The best smart home devices to automate and enhance your living space this year.',
                'content' => '<p>Smart home technology continues to evolve, making our lives more convenient and efficient. From voice assistants to security systems, these devices can transform your home.</p><p>Here are our top picks for the most innovative and useful smart home devices of 2025.</p>',
                'post_type' => 'list',
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'rating' => 4.6,
                'meta_title' => 'Best Smart Home Devices 2025 - Top 10 Picks',
                'meta_description' => 'Discover the best smart home devices for 2025. Expert reviews of top-rated automation and IoT products.',
                'views_count' => 2750,
                'clicks_count' => 88,
            ],
            [
                'title' => 'Best Baby Monitors: Safety and Peace of Mind',
                'slug' => 'best-baby-monitors-safety-peace-mind',
                'excerpt' => 'Comprehensive review of the best baby monitors for new parents, featuring video, audio, and smart features.',
                'content' => '<p>Choosing the right baby monitor is crucial for new parents. With so many options available, it\'s important to find one that offers both safety and peace of mind.</p><p>We\'ve tested the latest models to help you find the perfect baby monitor for your family.</p>',
                'post_type' => 'review',
                'status' => 'published',
                'published_at' => now()->subDays(6),
                'product_name' => 'Nanit Pro Smart Baby Monitor',
                'brand' => 'Nanit',
                'rating' => 4.7,
                'pros' => ['Crystal clear video', 'Smart alerts', 'Sleep tracking', 'Easy setup'],
                'cons' => ['Expensive', 'Requires subscription'],
                'price_text' => '$299.99',
                'meta_title' => 'Best Baby Monitors 2025 - Expert Reviews & Safety Guide',
                'meta_description' => 'Find the best baby monitors for safety and peace of mind. Expert reviews of top-rated video and audio monitors.',
                'views_count' => 1950,
                'clicks_count' => 67,
            ],
        ];

        foreach ($posts as $postData) {
            $post = Post::create(array_merge($postData, [
                'author_id' => $user->id,
            ]));

            // Attach categories based on post content
            if (str_contains($post->title, 'Hydroxyapatite') || str_contains($post->title, 'Probiotics')) {
                $post->categories()->attach($beautyCategory->id);
            } elseif (str_contains($post->title, 'Air Purifier') || str_contains($post->title, 'Smart Home')) {
                $post->categories()->attach($homeDecorCategory->id);
            } elseif (str_contains($post->title, 'Skincare')) {
                $post->categories()->attach($beautyCategory->id);
            } elseif (str_contains($post->title, 'Baby Monitor')) {
                $post->categories()->attach($homeDecorCategory->id);
            }

            // Attach featured category to top posts
            if ($post->rating >= 4.5) {
                $post->categories()->attach($featuredCategory->id);
            }
        }
    }
}
