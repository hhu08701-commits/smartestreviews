<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SlideshowSlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $slides = [
            [
                'title' => 'Best Products of 2025',
                'description' => 'Discover our top-rated products across all categories',
                'image' => asset('uploads/slideshow/best-products-2025.jpg'),
                'url' => route('posts.index'),
                'button_text' => 'Shop Now',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'New Arrivals',
                'description' => 'Check out the latest product reviews and recommendations',
                'image' => asset('uploads/slideshow/new-arrivals.jpg'),
                'url' => route('home'),
                'button_text' => 'Explore',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Featured Reviews',
                'description' => 'In-depth reviews of the most popular products',
                'image' => asset('uploads/slideshow/featured-reviews.jpg'),
                'url' => route('categories.show', 'featured-reviews'),
                'button_text' => 'Read Reviews',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $slide) {
            \App\Models\SlideshowSlide::create($slide);
        }
    }
}
