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
        $babyCategory = Category::where('slug', 'baby')->first();
        $familyCategory = Category::where('slug', 'family')->first();
        $fashionCategory = Category::where('slug', 'fashion')->first();

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
                'published_at' => \Carbon\Carbon::create(2025, 11, 1),
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
                'published_at' => \Carbon\Carbon::create(2025, 2, 18),
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
                'published_at' => \Carbon\Carbon::create(2025, 1, 15),
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
                'published_at' => \Carbon\Carbon::create(2024, 12, 10),
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
                'published_at' => \Carbon\Carbon::create(2024, 11, 25),
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
                'published_at' => \Carbon\Carbon::create(2022, 9, 8),
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
            // New sample posts matching the original website
            [
                'title' => 'Top 5 Multivitamin Supplements Of 2025',
                'slug' => 'top-5-multivitamin-supplements-of-2025',
                'excerpt' => 'Discover the best multivitamin supplements that will transform your health and wellness in 2025.',
                'content' => '<p>Multivitamins are essential for filling nutritional gaps in your diet. After extensive research and testing, we\'ve identified the top 5 multivitamin supplements that offer the best value, quality, and effectiveness.</p><p>These supplements are backed by science and have proven results from thousands of satisfied customers.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1550572017-edd951b05508?w=800&h=400&fit=crop',
                'post_type' => 'list',
                'status' => 'published',
                'published_at' => \Carbon\Carbon::create(2025, 10, 7),
                'rating' => 4.6,
                'is_featured' => true,
                'featured_order' => 1,
                'meta_title' => 'Best Multivitamin Supplements 2025 - Top 5 Picks',
                'meta_description' => 'Expert reviews of the top 5 multivitamin supplements for 2025. Find the best vitamins for your health needs.',
                'views_count' => 3200,
                'clicks_count' => 120,
            ],
            [
                'title' => 'Nuna Pipa Car Seat And Nuna Mixx Stroller Review: Your Baby Will Thank You!',
                'slug' => 'nuna-pipa-car-seat-nuna-mixx-stroller-review',
                'excerpt' => 'Comprehensive review of Nuna Pipa car seat and Mixx stroller - premium baby gear that combines safety, style, and functionality.',
                'content' => '<p>Nuna is a world-renowned lifestyle brand known for its innovative and stylish baby products. The Pipa car seat and Mixx stroller are two of their most popular products, and for good reason.</p><p>After months of testing, we can confidently say these products deliver on their promises of safety, convenience, and style.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=800&h=400&fit=crop',
                'post_type' => 'review',
                'status' => 'published',
                'published_at' => \Carbon\Carbon::create(2025, 8, 25),
                'product_name' => 'Nuna Pipa Car Seat & Mixx Stroller',
                'brand' => 'Nuna',
                'rating' => 4.8,
                'is_featured' => true,
                'featured_order' => 2,
                'pros' => ['Ultra-safe', 'Easy to install', 'Stylish design', 'Smooth ride'],
                'cons' => ['Premium price', 'Heavier than some options'],
                'price_text' => '$849.99',
                'meta_title' => 'Nuna Pipa Car Seat and Mixx Stroller Review 2025',
                'meta_description' => 'Complete review of Nuna Pipa car seat and Mixx stroller. Expert analysis of safety, features, and value.',
                'views_count' => 2800,
                'clicks_count' => 95,
            ],
            [
                'title' => 'Grammarly: The Tool You Need To Become a Better Writer',
                'slug' => 'grammarly-tool-become-better-writer',
                'excerpt' => 'Discover how Grammarly can transform your writing with real-time grammar checking, style suggestions, and clarity improvements.',
                'content' => '<p>Grammarly is a writing enhancement and grammar checker tool that helps millions of users improve their writing every day. Whether you\'re writing emails, reports, or creative content, Grammarly can help you communicate more effectively.</p><p>This comprehensive review covers everything you need to know about Grammarly\'s features, pricing, and effectiveness.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1455390582262-044cdead277a?w=800&h=400&fit=crop',
                'post_type' => 'review',
                'status' => 'published',
                'published_at' => \Carbon\Carbon::create(2024, 10, 24),
                'product_name' => 'Grammarly Premium',
                'brand' => 'Grammarly',
                'rating' => 4.7,
                'is_featured' => true,
                'featured_order' => 3,
                'pros' => ['Real-time checking', 'Multiple platforms', 'Style suggestions', 'Easy to use'],
                'cons' => ['Premium is pricey', 'Requires internet'],
                'price_text' => '$12/month',
                'meta_title' => 'Grammarly Review 2025 - Best Writing Tool Guide',
                'meta_description' => 'Complete Grammarly review. Learn how this writing tool can improve your grammar, style, and clarity.',
                'views_count' => 3500,
                'clicks_count' => 145,
            ],
            [
                'title' => 'Hair La Vie Review: The Revolutionary Hair Brand That Will Change Your Hair Game In No Time!',
                'slug' => 'hair-la-vie-review-revolutionary-hair-brand',
                'excerpt' => 'Discover Hair La Vie, the revolutionary hair care brand that promises to transform your hair with natural, science-backed formulas.',
                'content' => '<p>What is Hair La Vie? This innovative hair care brand has taken the beauty world by storm with its unique approach to hair health. Their products are formulated with natural ingredients and backed by scientific research.</p><p>Get 10% off your first order and discover why thousands of customers swear by Hair La Vie for healthier, stronger hair.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=800&h=400&fit=crop',
                'post_type' => 'review',
                'status' => 'published',
                'published_at' => \Carbon\Carbon::create(2024, 10, 23),
                'product_name' => 'Hair La Vie Complete System',
                'brand' => 'Hair La Vie',
                'rating' => 4.5,
                'is_featured' => true,
                'featured_order' => 4,
                'pros' => ['Natural ingredients', 'Visible results', 'Multiple products', 'Good reviews'],
                'cons' => ['Requires consistency', 'Moderate pricing'],
                'price_text' => '$89.99',
                'meta_title' => 'Hair La Vie Review 2025 - Complete Hair Care System',
                'meta_description' => 'Hair La Vie review: Discover this revolutionary hair brand and how it can transform your hair game.',
                'views_count' => 2400,
                'clicks_count' => 88,
            ],
            [
                'title' => 'Bodybuilding.com: Everything You Need To Know About This Supplement And Fitness Store!',
                'slug' => 'bodybuilding-com-supplement-fitness-store-review',
                'excerpt' => 'What is Bodybuilding.com? A comprehensive one-stop-shop for fitness and bodybuilding enthusiasts offering supplements, equipment, and expert advice.',
                'content' => '<p>What is Bodybuilding.com? This platform has been a trusted source for fitness enthusiasts for over two decades. They offer everything from supplements to workout plans to expert advice.</p><p>Whether you\'re a beginner or a seasoned athlete, Bodybuilding.com provides the tools and resources you need to reach your fitness goals.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=800&h=400&fit=crop',
                'post_type' => 'review',
                'status' => 'published',
                'published_at' => \Carbon\Carbon::create(2024, 8, 15),
                'product_name' => 'Bodybuilding.com Store',
                'brand' => 'Bodybuilding.com',
                'rating' => 4.6,
                'is_featured' => true,
                'featured_order' => 5,
                'pros' => ['Huge selection', 'Expert advice', 'Quality products', 'Good prices'],
                'cons' => ['Can be overwhelming', 'Shipping times vary'],
                'price_text' => 'Varies',
                'meta_title' => 'Bodybuilding.com Review 2025 - Complete Guide',
                'meta_description' => 'Everything you need to know about Bodybuilding.com. Complete review of supplements, fitness store, and resources.',
                'views_count' => 2900,
                'clicks_count' => 112,
            ],
            [
                'title' => 'Ole Henriksen Review: A Review Of The Best Skincare Products For Your Routine',
                'slug' => 'ole-henriksen-review-best-skincare-products',
                'excerpt' => 'What is Ole Henriksen? Discover this skincare brand known for its effective, results-driven products that deliver visible improvements.',
                'content' => '<p>What is Ole Henriksen? This skincare brand has built a reputation for creating effective, results-driven products. Their formulas combine powerful ingredients with innovative technology to deliver visible skin improvements.</p><p>From their popular Truth Serum to their moisturizers, we\'ve tested their best products to help you find what works for your skin.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1556228720-195a672e8a03?w=800&h=400&fit=crop',
                'post_type' => 'review',
                'status' => 'published',
                'published_at' => \Carbon\Carbon::create(2024, 7, 20),
                'product_name' => 'Ole Henriksen Skincare Collection',
                'brand' => 'Ole Henriksen',
                'rating' => 4.7,
                'is_featured' => true,
                'featured_order' => 6,
                'pros' => ['Effective formulas', 'Visible results', 'Luxurious feel', 'Wide range'],
                'cons' => ['Higher price point', 'Some products scented'],
                'price_text' => '$28-$68',
                'meta_title' => 'Ole Henriksen Review 2025 - Best Skincare Products',
                'meta_description' => 'Complete Ole Henriksen review. Discover the best skincare products from this popular brand.',
                'views_count' => 2700,
                'clicks_count' => 98,
            ],
            [
                'title' => 'SeroVital Review: How Effective is it?',
                'slug' => 'serovital-review-how-effective',
                'excerpt' => 'SeroVital Advanced is a daily supplement taken twice a day. Discover if this anti-aging supplement lives up to its promises.',
                'content' => '<p>SeroVital Advanced is a daily supplement taken twice a day that claims to support healthy aging from within. This comprehensive review examines the science behind the product and whether it delivers on its promises.</p><p>We\'ve analyzed the ingredients, customer reviews, and scientific studies to give you an honest assessment of SeroVital\'s effectiveness.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=800&h=400&fit=crop',
                'post_type' => 'review',
                'status' => 'published',
                'published_at' => \Carbon\Carbon::create(2024, 2, 14),
                'product_name' => 'SeroVital Advanced',
                'brand' => 'SeroVital',
                'rating' => 4.3,
                'is_featured' => true,
                'featured_order' => 7,
                'pros' => ['Easy to take', 'Good reviews', 'Multiple benefits', 'Convenient dosing'],
                'cons' => ['Results vary', 'Premium price', 'Takes time to see effects'],
                'price_text' => '$99/month',
                'meta_title' => 'SeroVital Review 2025 - Is It Effective?',
                'meta_description' => 'SeroVital review: Discover how effective this anti-aging supplement really is. Expert analysis and customer reviews.',
                'views_count' => 2200,
                'clicks_count' => 75,
            ],
            [
                'title' => 'Saie Review: Everything You Need To Know About This Clean Beauty Brand',
                'slug' => 'saie-review-clean-beauty-brand',
                'excerpt' => 'Saie is a modern beauty brand that offers clean and effective products. Discover why this brand is gaining popularity among beauty enthusiasts.',
                'content' => '<p>Saie is a modern beauty brand that offers clean and effective products. Founded on the principle that beauty should be both good for you and good for the planet, Saie has created a loyal following.</p><p>From their popular foundation to their skincare-infused makeup, we explore what makes Saie stand out in the clean beauty market.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1522338243-673a cd68e0f3?w=800&h=400&fit=crop',
                'post_type' => 'review',
                'status' => 'published',
                'published_at' => \Carbon\Carbon::create(2024, 1, 28),
                'product_name' => 'Saie Beauty Collection',
                'brand' => 'Saie',
                'rating' => 4.6,
                'is_featured' => true,
                'featured_order' => 8,
                'pros' => ['Clean ingredients', 'Effective formulas', 'Eco-friendly', 'Great shades'],
                'cons' => ['Limited shade range', 'Can be pricey'],
                'price_text' => '$24-$38',
                'meta_title' => 'Saie Review 2025 - Clean Beauty Brand Guide',
                'meta_description' => 'Complete Saie review. Everything you need to know about this popular clean beauty brand.',
                'views_count' => 2600,
                'clicks_count' => 92,
            ],
            [
                'title' => 'Ilia Beauty: Is It Worth The Hype? Our Review',
                'slug' => 'ilia-beauty-worth-hype-review',
                'excerpt' => 'Comprehensive review of Ilia Beauty - is this clean beauty brand worth the investment? We tested their best-selling products to find out.',
                'content' => '<p>Ilia Beauty has taken the clean beauty world by storm with their skincare-infused makeup. But is it worth the hype? We\'ve tested their best-selling products including their foundation, mascara, and lip products.</p><p>Our honest review covers everything from formula quality to shade range to value for money.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=800&h=400&fit=crop',
                'post_type' => 'review',
                'status' => 'published',
                'published_at' => \Carbon\Carbon::create(2023, 12, 18),
                'product_name' => 'Ilia Beauty Collection',
                'brand' => 'Ilia',
                'rating' => 4.5,
                'is_featured' => true,
                'featured_order' => 9,
                'pros' => ['Clean ingredients', 'Skincare benefits', 'Great packaging', 'Good coverage'],
                'cons' => ['Premium pricing', 'Limited availability'],
                'price_text' => '$28-$48',
                'meta_title' => 'Ilia Beauty Review 2025 - Is It Worth The Hype?',
                'meta_description' => 'Ilia Beauty review: Is this clean beauty brand worth the investment? Expert analysis and product testing.',
                'views_count' => 3100,
                'clicks_count' => 118,
            ],
            [
                'title' => 'Paula\'s Choice: Best sellers You Need To Try',
                'slug' => 'paulas-choice-best-sellers-need-try',
                'excerpt' => 'Discover Paula\'s Choice best-selling products that have become cult favorites in the skincare community.',
                'content' => '<p>Paula\'s Choice has built a reputation for science-backed skincare that delivers real results. Their best-selling products have become cult favorites for a reason.</p><p>We\'ve tested their most popular items to help you find the perfect additions to your skincare routine.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1556228841-cfe6ae80d69a?w=800&h=400&fit=crop',
                'post_type' => 'review',
                'status' => 'published',
                'published_at' => \Carbon\Carbon::create(2023, 11, 25),
                'product_name' => 'Paula\'s Choice Best Sellers',
                'brand' => 'Paula\'s Choice',
                'rating' => 4.8,
                'is_featured' => true,
                'featured_order' => 10,
                'pros' => ['Science-backed', 'Highly effective', 'No fragrance', 'Great value'],
                'cons' => ['Can be strong', 'Requires patch testing'],
                'price_text' => '$22-$65',
                'meta_title' => 'Paula\'s Choice Best Sellers Review 2025',
                'meta_description' => 'Paula\'s Choice best sellers review. Discover the top products you need to try from this popular skincare brand.',
                'views_count' => 3400,
                'clicks_count' => 135,
            ],
            [
                'title' => 'Layla Sleep Review: How It Will Change The Way You Sleep For Good!',
                'slug' => 'layla-sleep-review-change-way-sleep',
                'excerpt' => 'What is Layla Sleep? An online mattress company that focuses on providing premium sleep solutions with their unique dual-sided mattress.',
                'content' => '<p>What is Layla Sleep? This innovative mattress company has revolutionized sleep with their dual-sided memory foam mattress. One side is firm, the other is soft, giving you the flexibility to choose your perfect comfort level.</p><p>After months of testing, we can confidently say that Layla Sleep delivers on their promise of better sleep.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=800&h=400&fit=crop',
                'post_type' => 'review',
                'status' => 'published',
                'published_at' => \Carbon\Carbon::create(2023, 10, 10),
                'product_name' => 'Layla Sleep Mattress',
                'brand' => 'Layla Sleep',
                'rating' => 4.7,
                'is_featured' => true,
                'featured_order' => 11,
                'pros' => ['Dual-sided', 'Great support', 'Cooling gel', '100-night trial'],
                'cons' => ['Heavy to move', 'Off-gassing initially'],
                'price_text' => '$949-$1499',
                'meta_title' => 'Layla Sleep Review 2025 - Dual-Sided Mattress Guide',
                'meta_description' => 'Layla Sleep review: How this dual-sided mattress will change the way you sleep. Expert analysis and testing.',
                'views_count' => 2300,
                'clicks_count' => 85,
            ],
            [
                'title' => 'Ritual Vitamins Review And How It Changed Me!',
                'slug' => 'ritual-vitamins-review-how-changed-me',
                'excerpt' => 'Honest review of Ritual vitamins and how incorporating this supplement into my daily routine transformed my health and energy levels.',
                'content' => '<p>After years of trying different multivitamins, I finally found Ritual. This honest review shares my experience with Ritual vitamins and how they\'ve genuinely changed my health and energy levels.</p><p>From the clean ingredients to the traceable sourcing, Ritual stands out in the supplement market for good reason.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=800&h=400&fit=crop',
                'post_type' => 'review',
                'status' => 'published',
                'published_at' => \Carbon\Carbon::create(2022, 4, 15),
                'product_name' => 'Ritual Multivitamin',
                'brand' => 'Ritual',
                'rating' => 4.6,
                'is_featured' => true,
                'featured_order' => 12,
                'pros' => ['Clean ingredients', 'Traceable sourcing', 'Easy to digest', 'Subscription option'],
                'cons' => ['Premium price', 'Limited to essential vitamins'],
                'price_text' => '$30/month',
                'meta_title' => 'Ritual Vitamins Review 2025 - How It Changed Me',
                'meta_description' => 'Honest Ritual vitamins review. Discover how this supplement changed my health and energy levels.',
                'views_count' => 2500,
                'clicks_count' => 96,
            ],
            [
                'title' => 'SKIMS: Everything You Need To Know About Kim Kardashian\'s Underwear, Loungewear, And Shapewear Brand',
                'slug' => 'skims-everything-need-know-kim-kardashian-brand',
                'excerpt' => 'Complete guide to SKIMS, Kim Kardashian\'s revolutionary shapewear, loungewear, and underwear brand that\'s taking the fashion world by storm.',
                'content' => '<p>SKIMS has become one of the most talked-about brands in fashion, thanks to Kim Kardashian\'s vision of inclusive, comfortable, and stylish undergarments. This comprehensive guide covers everything you need to know about SKIMS products.</p><p>From their innovative shapewear to their cozy loungewear, we explore what makes SKIMS special and whether it\'s worth the investment.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1558769132-cb1aea458c5e?w=800&h=400&fit=crop',
                'post_type' => 'review',
                'status' => 'published',
                'published_at' => \Carbon\Carbon::create(2022, 2, 20),
                'product_name' => 'SKIMS Collection',
                'brand' => 'SKIMS',
                'rating' => 4.5,
                'is_featured' => true,
                'featured_order' => 13,
                'pros' => ['Inclusive sizing', 'Comfortable', 'Stylish', 'Quality materials'],
                'cons' => ['Higher price point', 'Limited availability'],
                'price_text' => '$18-$98',
                'meta_title' => 'SKIMS Review 2025 - Kim Kardashian Brand Guide',
                'meta_description' => 'Complete SKIMS review. Everything you need to know about Kim Kardashian\'s shapewear and loungewear brand.',
                'views_count' => 3800,
                'clicks_count' => 158,
            ],
            [
                'title' => 'The 2025 List Best Chocolates you need to try at least once.',
                'slug' => '2025-list-best-chocolates-try-least-once',
                'excerpt' => 'Discover the best chocolates of 2025 that every chocolate lover needs to try at least once in their lifetime.',
                'content' => '<p>Chocolate is one of life\'s greatest pleasures, and we\'ve curated a list of the absolute best chocolates you need to try at least once. From artisanal bars to luxury truffles, these chocolates represent the pinnacle of flavor and craftsmanship.</p><p>Whether you\'re a dark chocolate aficionado or prefer milk chocolate, there\'s something on this list for everyone.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1511381939415-e44015466834?w=800&h=400&fit=crop',
                'post_type' => 'list',
                'status' => 'published',
                'published_at' => \Carbon\Carbon::create(2022, 1, 10),
                'rating' => 4.4,
                'meta_title' => 'Best Chocolates 2025 - Must-Try List',
                'meta_description' => 'The best chocolates you need to try at least once. Complete guide to premium and artisanal chocolates.',
                'views_count' => 2100,
                'clicks_count' => 72,
            ],
            [
                'title' => 'Cometeer Coffee Review',
                'slug' => 'cometeer-coffee-review',
                'excerpt' => 'Comprehensive review of Cometeer Coffee - the innovative frozen coffee discs that deliver barista-quality coffee at home.',
                'content' => '<p>Cometeer has revolutionized the home coffee experience with their innovative frozen coffee discs. These flash-frozen coffee capsules promise barista-quality coffee without the hassle of grinding beans or complicated equipment.</p><p>We\'ve tested Cometeer extensively to see if it lives up to the hype and delivers truly great coffee.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1497935586351-b67a49e012bf?w=800&h=400&fit=crop',
                'post_type' => 'review',
                'status' => 'published',
                'published_at' => \Carbon\Carbon::create(2021, 6, 25),
                'product_name' => 'Cometeer Coffee Discs',
                'brand' => 'Cometeer',
                'rating' => 4.5,
                'pros' => ['Barista quality', 'Convenient', 'Great flavor', 'Easy to use'],
                'cons' => ['Premium price', 'Requires hot water'],
                'price_text' => '$11.50/pack',
                'meta_title' => 'Cometeer Coffee Review 2025',
                'meta_description' => 'Cometeer coffee review. Discover if these frozen coffee discs deliver barista-quality coffee at home.',
                'views_count' => 1900,
                'clicks_count' => 65,
            ],
            [
                'title' => 'What I Actually Used In My Hospital Bag... (and what I didn\'t)',
                'slug' => 'what-actually-used-hospital-bag-what-didnt',
                'excerpt' => 'Being the planner that I am, I researched extensively what to pack in my hospital bag. Here\'s what I actually used and what I didn\'t need.',
                'content' => '<p>Being the planner that I am, I researched extensively what to pack in my hospital bag. After actually going through the experience, I can now share what I actually used and what I didn\'t need.</p><p>This honest account will help expecting mothers pack more efficiently and avoid overpacking unnecessary items.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1544568100-847a948585b9?w=800&h=400&fit=crop',
                'post_type' => 'how-to',
                'status' => 'published',
                'published_at' => \Carbon\Carbon::create(2022, 11, 12),
                'is_featured' => true,
                'featured_order' => 14,
                'meta_title' => 'Hospital Bag Checklist - What I Actually Used',
                'meta_description' => 'Honest hospital bag checklist. What I actually used and what I didn\'t need during my hospital stay.',
                'views_count' => 4100,
                'clicks_count' => 165,
            ],
        ];

        foreach ($posts as $postData) {
            $post = Post::updateOrCreate(
                ['slug' => $postData['slug']],
                array_merge($postData, [
                    'author_id' => $user->id,
                ])
            );

            // Attach categories based on post content
            $title = strtolower($post->title);
            $categoryIds = [];
            
            if (str_contains($title, 'hydroxyapatite') || str_contains($title, 'probiotics') || str_contains($title, 'multivitamin') || str_contains($title, 'vitamin') || str_contains($title, 'serovital') || str_contains($title, 'ritual')) {
                if ($beautyCategory) $categoryIds[] = $beautyCategory->id;
            }
            
            if (str_contains($title, 'skincare') || str_contains($title, 'beauty') || str_contains($title, 'ilia') || str_contains($title, 'saie') || str_contains($title, 'ole henriksen') || str_contains($title, 'paula') || str_contains($title, 'hair la vie')) {
                if ($beautyCategory && !in_array($beautyCategory->id, $categoryIds)) $categoryIds[] = $beautyCategory->id;
            }
            
            if (str_contains($title, 'air purifier') || str_contains($title, 'smart home') || str_contains($title, 'layla sleep') || str_contains($title, 'mattress')) {
                if ($homeDecorCategory) $categoryIds[] = $homeDecorCategory->id;
            }
            
            if (str_contains($title, 'baby monitor') || str_contains($title, 'nuna') || str_contains($title, 'car seat') || str_contains($title, 'stroller') || str_contains($title, 'hospital bag')) {
                if ($babyCategory) $categoryIds[] = $babyCategory->id;
                if ($familyCategory) $categoryIds[] = $familyCategory->id;
            }
            
            if (str_contains($title, 'skims') || str_contains($title, 'fashion')) {
                if ($fashionCategory) $categoryIds[] = $fashionCategory->id;
            }
            
            if (str_contains($title, 'grammarly')) {
                if ($electronicsCategory) $categoryIds[] = $electronicsCategory->id;
            }
            
            if (str_contains($title, 'bodybuilding') || str_contains($title, 'supplement') || str_contains($title, 'fitness')) {
                if ($beautyCategory && !in_array($beautyCategory->id, $categoryIds)) $categoryIds[] = $beautyCategory->id;
            }

            // Attach featured category to featured posts or high-rated posts
            if (($post->is_featured || ($post->rating && $post->rating >= 4.5)) && $featuredCategory) {
                $categoryIds[] = $featuredCategory->id;
            }
            
            // Sync categories (removes duplicates automatically)
            if (!empty($categoryIds)) {
                $post->categories()->syncWithoutDetaching(array_unique($categoryIds));
            }
        }
    }
}
