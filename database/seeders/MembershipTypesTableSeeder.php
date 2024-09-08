<?php

namespace Database\Seeders;

use App\Models\MembershipType;
use Illuminate\Database\Seeder;

class MembershipTypesTableSeeder extends Seeder
{
    /**
     * Seed the membership_types table.
     *
     * @return void
     */
    public function run()
    {
        MembershipType::insert([
            ['name' => 'Standard', 'discount' => 0, 'created_at' => now(), 'updated_at' => now(), 'color' => 'bg-gray-200 text-gray-700'],
            ['name' => 'Premium', 'discount' => 10, 'created_at' => now(), 'updated_at' => now(), 'color' => 'bg-violet-400 text-white'],
            ['name' => 'VIP', 'discount' => 20, 'created_at' => now(), 'updated_at' => now(), 'color' => 'bg-yellow-500 text-gray-500'],
        ]);
    }
}
