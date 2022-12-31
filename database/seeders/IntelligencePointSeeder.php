<?php

namespace Database\Seeders;

use App\Models\Intelligence;
use App\Models\IntelligencePoint;
use App\Models\IntelligencePointName;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IntelligencePointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Intelligence::query()->pluck('id') as $intelligence) {
            foreach (IntelligencePointName::query()->limit(rand(5, 8))->pluck('id') as $intelligencePointName) {
                IntelligencePoint::factory()->create([
                    'intelligence_id' => $intelligence,
                    'intelligence_point_name_id' => $intelligencePointName,
                ]);
            }
        }
    }
}
