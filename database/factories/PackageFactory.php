<?php

namespace Database\Factories;

use App\Models\Intelligence;
use App\Models\Package;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Package>
 */
class PackageFactory extends Factory
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
            'title' => $this->faker->slug,
            'age' => $this->faker->numberBetween(3, 22),
            'price' => $this->faker->numberBetween(1, 9) . "0000",
        ];
    }

    public function withIntelligences()
    {
        return $this->afterCreating(function (Package $package) {
            $intelligences = Intelligence::query()->limit(rand(3, 8))->inRandomOrder()->pluck('id');
            $package->intelligences()
                ->withTimestamps()
                ->sync($intelligences);
        });
    }
}
