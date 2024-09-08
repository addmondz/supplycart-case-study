<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MembershipType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as FakerFactory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MembershipTypesTableSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
        ]);

        $faker = FakerFactory::create();

        // Get all membership types
        $membershipTypes = MembershipType::all();

        foreach ($membershipTypes as $membershipType) {
            User::factory()->create([
                'name' => $faker->name,
                'email' => strtolower($membershipType->name) . '_user@email.com',
                'membership_type_id' => $membershipType->id,
                'password' => Hash::make('12345678'),
            ]);
        }
    }
}
