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
        $this->_createSuperAdmin();
        $this->_createPersonnel();
        $this->_createRahjoo();
    }

    /**
     * @return void
     */
    private function _createSuperAdmin(): void
    {
        $user = User::factory()->create(['mobile' => '+989123456789']);
        $user->assignRole(Role::SUPER_ADMIN);
    }

    /**
     * @return void
     */
    private function _createPersonnel(): void
    {
        $user = User::factory()->create(['mobile' => '+989111111111']);
        $user->assignRole(Role::PERSONNEL);
    }

    /**
     * @return void
     */
    private function _createRahjoo(): void
    {
        $user = User::factory()->create(['mobile' => '+989222222222']);
        $user->assignRole(Role::RAHJOO);
    }
}
