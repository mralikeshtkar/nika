<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        file_put_contents(storage_path('logs/laravel.log'),'');
//        $this->_generateToken('+989123456789');
        $this->_createSuperAdmin('+989123456789');
        $this->_createPersonnel();
        $this->_createRahjoo();
        $this->_createSuperAdmin('+989112352382');
    }

    /**
     * @param $mobile
     * @return void
     */
    private function _createSuperAdmin($mobile): void
    {
        $user = User::factory()->create(['mobile' => $mobile]);
        logger($user->mobile, ['token' => $user->generateToken()]);
        $user->assignRole(Role::SUPER_ADMIN);
    }

    /**
     * @param $mobile
     * @return void
     */
    private function _generateToken($mobile): void
    {
        $user = User::query()->where('mobile' , $mobile)->firstOrFail();
        logger($user->mobile, ['token' => $user->generateToken()]);
    }

    /**
     * @return void
     */
    private function _createPersonnel(): void
    {
        $user = User::factory()->create(['mobile' => '+989111111111']);
        logger($user->mobile, ['token' => $user->generateToken()]);
        $user->assignRole(Role::PERSONNEL);
    }

    /**
     * @return void
     */
    private function _createRahjoo(): void
    {
        $user = User::factory()->create(['mobile' => '+989222222222']);
        logger($user->mobile, ['token' => $user->generateToken()]);
        $user->assignRole(Role::RAHJOO);
    }
}
