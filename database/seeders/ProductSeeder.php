<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $categories = Category::all();
        $brands = Brand::all();

        // List of realistic product names
        $productNames = [
            'T-Shirt',
            'Jeans',
            'Jacket',
            'Sneakers',
            'Sweater',
            'Shorts',
            'Hat',
            'Sunglasses',
            'Dress',
            'Suit',
            'Scarf',
            'Gloves',
            'Watch',
            'Belt',
            'Bag',
            'Shoes',
            'Skirt',
            'Coat',
            'Cardigan',
            'Vest',
            'Boots',
            'Blouse',
            'Blazer',
            'Polo Shirt',
            'Tank Top',
            'Hoodie',
            'Sweatshirt',
            'Cargo Pants',
            'Chinos',
            'Leggings',
            'Track Pants',
            'Overalls',
            'Kimono'
        ];

        foreach (range(1, 50) as $index) {
            Product::create([
                'name' => $faker->randomElement($productNames),
                'description' => $faker->sentence,
                'price' => $faker->randomFloat(2, 10, 1000),
                'category_id' => $categories->random()->id,
                'brand_id' => $brands->random()->id,
            ]);
        }
    }
}
