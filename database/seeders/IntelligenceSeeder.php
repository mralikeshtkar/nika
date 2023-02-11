<?php

namespace Database\Seeders;

use App\Models\Intelligence;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class IntelligenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 8) as $item) {
            Intelligence::factory()->create([
                'title'=>trans("Intelligence") . " " . $item,
            ]);
        }
    }
}
