<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\Personnel\PersonnelComputerLevel;
use App\Enums\User\UserBackground;
use App\Enums\User\UserColor;
use App\Models\Exercise;
use App\Models\Intelligence;
use App\Models\IntelligencePoint;
use App\Models\IntelligencePointName;
use App\Models\Package;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        $this->_truncateTables();

        $this->call([
            PermissionSeeder::class,
            UserSeeder::class,
            ProvinceSeeder::class,
//            GradeSeeder::class,
//            IntelligenceSeeder::class,
//            PackageSeeder::class,
//            ExerciseSeeder::class,
//            IntelligencePointNameSeeder::class,
//            IntelligencePointSeeder::class,
        ]);
    }

    /**
     * @return void
     */
    private function _truncateTables(): void
    {
        Schema::disableForeignKeyConstraints();
        $tables = DB::select('SHOW TABLES');
        foreach ($tables as $name) {
            if (current($name) == 'migrations') {
                continue;
            }
            DB::table(current($name))->truncate();
        }
        Schema::enableForeignKeyConstraints();
    }
}
