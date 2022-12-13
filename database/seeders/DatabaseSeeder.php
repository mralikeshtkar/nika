<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\Personnel\PersonnelComputerLevel;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
//            ProvinceSeeder::class,
            PermissionSeeder::class,
//            GradeSeeder::class,
//            UserSeeder::class,
        ]);
    }
}
