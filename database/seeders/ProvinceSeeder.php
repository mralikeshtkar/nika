<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::transaction(function () {
                $provinces = json_decode(file_get_contents(storage_path('cities.json')), true);
                foreach ($provinces as $province) {
                    $province_id = Province::factory()->create(['name' => $province['name']])->id;
                    foreach ($province['cities'] as $city) {
                        City::factory()->create(['province_id' => $province_id, 'name' => $city]);
                    }
                }
            });
        } catch (\Throwable $e) {
            $this->command->error($e->getMessage());
        }
    }
}
