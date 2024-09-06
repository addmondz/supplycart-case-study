<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run()
    {
        // Array of legitimate brand names
        $brands = [
            'Nike',
            'Adidas',
            'Apple',
            'Samsung',
            'Sony',
            'Microsoft',
            'Intel',
            'Dell',
            'HP',
            'Google',
        ];

        // Insert each brand name into the database
        foreach ($brands as $brandName) {
            Brand::create([
                'name' => $brandName,
            ]);
        }
    }
}
