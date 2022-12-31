<?php

namespace Database\Factories;

use App\Models\Intelligence;
use App\Models\IntelligencePointName;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IntelligencePoint>
 */
class IntelligencePointFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::query()->inRandomOrder()->first()->id,
            'intelligence_id' => Intelligence::query()->inRandomOrder()->first()->id,
            'intelligence_point_name_id' => IntelligencePointName::query()->inRandomOrder()->first()->id,
            'max_point' => $this->faker->numberBetween(20, 100),
        ];
    }
}
