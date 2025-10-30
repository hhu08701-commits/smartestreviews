<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Beauty',
                'slug' => 'beauty',
                'description' => 'Skincare, makeup, haircare, and personal care products',
                'color' => '#EC4899',
                'icon' => 'sparkles',
                'sort_order' => 1,
            ],
            [
                'name' => 'Home Decor',
                'slug' => 'home-decor',
                'description' => 'Furniture, decorations, and home improvement products',
                'color' => '#10B981',
                'icon' => 'home',
                'sort_order' => 2,
            ],
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Gadgets, devices, and electronic accessories',
                'color' => '#3B82F6',
                'icon' => 'cpu-chip',
                'sort_order' => 3,
            ],
            [
                'name' => 'Baby',
                'slug' => 'baby',
                'description' => 'Baby gear, toys, and childcare products',
                'color' => '#F59E0B',
                'icon' => 'baby',
                'sort_order' => 4,
            ],
            [
                'name' => 'Family',
                'slug' => 'family',
                'description' => 'Products for the whole family',
                'color' => '#8B5CF6',
                'icon' => 'users',
                'sort_order' => 5,
            ],
            [
                'name' => 'Fashion',
                'slug' => 'fashion',
                'description' => 'Clothing, accessories, and style items',
                'color' => '#EF4444',
                'icon' => 'shopping-bag',
                'sort_order' => 6,
            ],
            [
                'name' => 'Featured Reviews',
                'slug' => 'featured-reviews',
                'description' => 'Our top picks and featured product reviews',
                'color' => '#F97316',
                'icon' => 'star',
                'sort_order' => 7,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
