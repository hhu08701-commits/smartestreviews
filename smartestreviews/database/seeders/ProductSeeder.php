<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Baby Monitor Pro',
                'brand' => 'SafetyFirst',
                'description' => 'Advanced baby monitor with night vision and two-way audio communication.',
                'image_url' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400',
                'price_text' => '$89.99',
                'price' => 89.99,
                'currency' => 'USD',
                'rating' => 4.5,
                'review_count' => 1247,
                'sku' => 'BM-PRO-001',
                'asin' => 'B08XYZ123',
                'specifications' => json_encode([
                    'Night Vision' => 'Yes',
                    'Range' => '1000ft',
                    'Battery Life' => '12 hours',
                    'Two-way Audio' => 'Yes',
                    'Mobile App' => 'Yes'
                ]),
                'features' => json_encode([
                    'HD Night Vision',
                    'Two-way Audio',
                    'Temperature Monitoring',
                    'Cry Detection',
                    'Mobile App Control'
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'UltraClean Vacuum',
                'brand' => 'CleanTech',
                'description' => 'Powerful cordless vacuum cleaner with advanced filtration system.',
                'image_url' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400',
                'price_text' => '$299.99',
                'price' => 299.99,
                'currency' => 'USD',
                'rating' => 4.3,
                'review_count' => 892,
                'sku' => 'UC-VAC-002',
                'asin' => 'B08ABC456',
                'specifications' => json_encode([
                    'Power' => '200W',
                    'Battery Life' => '45 minutes',
                    'Dust Capacity' => '0.8L',
                    'Weight' => '5.2 lbs',
                    'Filtration' => 'HEPA'
                ]),
                'features' => json_encode([
                    'Cordless Design',
                    'HEPA Filtration',
                    'LED Headlight',
                    'Easy Empty Dustbin',
                    'Multiple Attachments'
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'SoundWave Headphones',
                'brand' => 'AudioMax',
                'description' => 'Premium wireless headphones with noise cancellation and 30-hour battery life.',
                'image_url' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400',
                'price_text' => '$199.99',
                'price' => 199.99,
                'currency' => 'USD',
                'rating' => 4.7,
                'review_count' => 2156,
                'sku' => 'SW-HP-003',
                'asin' => 'B08DEF789',
                'specifications' => json_encode([
                    'Driver Size' => '40mm',
                    'Frequency Response' => '20Hz-20kHz',
                    'Battery Life' => '30 hours',
                    'Charging Time' => '2 hours',
                    'Weight' => '250g'
                ]),
                'features' => json_encode([
                    'Active Noise Cancellation',
                    '30-Hour Battery Life',
                    'Quick Charge',
                    'Comfortable Earcups',
                    'Premium Sound Quality'
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'BlendMaster Pro',
                'brand' => 'KitchenPro',
                'description' => 'High-performance blender with 6 blades and 1500W motor.',
                'image_url' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400',
                'price_text' => '$149.99',
                'price' => 149.99,
                'currency' => 'USD',
                'rating' => 4.4,
                'review_count' => 743,
                'sku' => 'BM-BL-004',
                'asin' => 'B08GHI012',
                'specifications' => json_encode([
                    'Motor Power' => '1500W',
                    'Blade Count' => '6',
                    'Capacity' => '64oz',
                    'Speed Settings' => '10',
                    'Material' => 'Stainless Steel'
                ]),
                'features' => json_encode([
                    '6-Blade System',
                    '10 Speed Settings',
                    'Large Capacity',
                    'Easy Clean Design',
                    'Durable Construction'
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'SmartWatch Series 8',
                'brand' => 'TechWear',
                'description' => 'Advanced smartwatch with health monitoring and GPS tracking.',
                'image_url' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400',
                'price_text' => '$399.99',
                'price' => 399.99,
                'currency' => 'USD',
                'rating' => 4.6,
                'review_count' => 1834,
                'sku' => 'SW-S8-005',
                'asin' => 'B08JKL345',
                'specifications' => json_encode([
                    'Display Size' => '1.9 inch',
                    'Battery Life' => '18 hours',
                    'Water Resistance' => '50m',
                    'GPS' => 'Yes',
                    'Heart Rate Monitor' => 'Yes'
                ]),
                'features' => json_encode([
                    'Health Monitoring',
                    'GPS Tracking',
                    'Water Resistant',
                    'Long Battery Life',
                    'Customizable Watch Faces'
                ]),
                'is_active' => true,
            ],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}
