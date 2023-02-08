<?php

namespace Database\Seeders;

use App\Models\Intelligence;
use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(0, 3) as $i) {
            Package::factory()->withIntelligences()->create(['title' => trans("Package") . $i]);
        }
    }
}
