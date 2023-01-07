<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Package::with('intelligences')->get() as $package) {
            foreach ($package->intelligences as $intelligence) {
                foreach (range(1, rand(5,10)) as $i) {
                    Exercise::factory()->create([
                        'intelligence_package_id' => $intelligence->pivot->pivot_id,
                        'title' => trans("Exercise") . " " . $i,
                    ]);
                }
            }
        }
    }
}
