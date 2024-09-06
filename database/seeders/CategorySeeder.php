<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Array of legitimate category names
        $categories = [
            'Electronics',
            'Clothing',
            'Home & Kitchen',
            'Sports & Outdoors',
            'Books',
            'Health & Personal Care',
            'Automotive',
            'Toys & Games',
            'Beauty & Personal Care',
            'Grocery',
        ];

        // Insert each category name into the database
        foreach ($categories as $categoryName) {
            Category::create([
                'name' => $categoryName,
            ]);
        }
    }
}
