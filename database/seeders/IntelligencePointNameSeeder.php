<?php

namespace Database\Seeders;

use App\Models\IntelligencePointName;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IntelligencePointNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = ["سرعت", "دقت", "تحلیل", "بررسی", "زمان", "تفکر", "دانش", "استعداد"];
        foreach ($names as $name) {
            IntelligencePointName::factory()->create(['name' => $name]);
        }
    }
}
