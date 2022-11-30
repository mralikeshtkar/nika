<?php

namespace Database\Seeders;

use App\Enums\Permission;
use App\Enums\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Permission::asArray() as $permission) {
            \App\Models\Permission::findOrCreate($permission);
        }
        foreach (\App\Models\Role::ROLES as $role) {
            $item = \App\Models\Role::query()->firstOrCreate([
                'name' => $role['name'],
            ], [
                'name' => $role['name'],
                'name_fa' => Role::getDescription($role['name']),
                'is_locked' => Arr::get($role, 'is_locked', false),
            ]);
            $item->syncPermissions(Arr::get($role, 'permissions', []));
        }
    }
}
