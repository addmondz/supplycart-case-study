<?php

namespace Database\Factories;

use App\Models\MembershipType;
use Illuminate\Database\Eloquent\Factories\Factory;

class MembershipTypeFactory extends Factory
{
    protected $model = MembershipType::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'discount' => $this->faker->numberBetween(0, 50),
            'color' => $this->faker->safeColorName,
        ];
    }
}
