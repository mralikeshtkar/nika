<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\Personnel\PersonnelComputerLevel;
use App\Models\Intelligence;
use App\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Intelligence::query()->truncate();
        Package::query()->truncate();
        Schema::enableForeignKeyConstraints();
        $this->call([
//            ProvinceSeeder::class,
//            PermissionSeeder::class,
//            GradeSeeder::class,
//            UserSeeder::class,
            IntelligenceSeeder::class,
            PackageSeeder::class,
        ]);
    }
}
