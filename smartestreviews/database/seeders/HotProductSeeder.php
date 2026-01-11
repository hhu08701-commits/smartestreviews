<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HotProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hotProducts = [
            [
                'name' => 'Dyson V15 Detect',
                'brand' => 'Dyson',
                'image' => asset('uploads/hot-products/dyson-v15.jpg'),
                'rating' => 4.8,
                'price' => 'From $749',
                'trending' => 1,
                'url' => 'https://www.dyson.com',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Apple AirPods Pro',
                'brand' => 'Apple',
                'image' => asset('uploads/hot-products/airpods-pro.jpg'),
                'rating' => 4.9,
                'price' => '$249',
                'trending' => 1,
                'url' => 'https://www.apple.com',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'brand' => 'Samsung',
                'image' => asset('uploads/hot-products/galaxy-s24.jpg'),
                'rating' => 4.7,
                'price' => 'From $1,199',
                'trending' => 1,
                'url' => 'https://www.samsung.com',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Nest Thermostat',
                'brand' => 'Google',
                'image' => asset('uploads/hot-products/nest-thermostat.jpg'),
                'rating' => 4.6,
                'price' => '$129',
                'trending' => 1,
                'url' => 'https://store.google.com',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'LG OLED C3 TV',
                'brand' => 'LG',
                'image' => asset('uploads/hot-products/lg-oled-c3.jpg'),
                'rating' => 4.8,
                'price' => 'From $1,499',
                'trending' => 1,
                'url' => 'https://www.lg.com',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Fitbit Charge 6',
                'brand' => 'Fitbit',
                'image' => asset('uploads/hot-products/fitbit-charge6.jpg'),
                'rating' => 4.5,
                'price' => '$159',
                'trending' => 1,
                'url' => 'https://www.fitbit.com',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Ring Doorbell Pro',
                'brand' => 'Ring',
                'image' => asset('uploads/hot-products/ring-doorbell.jpg'),
                'rating' => 4.4,
                'price' => '$249',
                'trending' => 1,
                'url' => 'https://ring.com',
                'sort_order' => 7,
                'is_active' => true,
            ],
        ];

        foreach ($hotProducts as $product) {
            \App\Models\HotProduct::create($product);
        }
    }
}
