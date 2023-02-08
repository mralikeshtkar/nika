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
        Schema::table('users', function (Blueprint $table) {
            $table->string('background')->default(UserBackground::Black);
            $table->string('color')->default(UserColor::Pink);
        });
        dd("ok");
        $this->call([
            ProvinceSeeder::class,
            PermissionSeeder::class,
            GradeSeeder::class,
            UserSeeder::class,
            IntelligenceSeeder::class,
            PackageSeeder::class,
            ExerciseSeeder::class,
            IntelligencePointNameSeeder::class,
            IntelligencePointSeeder::class,
        ]);
    }
}
