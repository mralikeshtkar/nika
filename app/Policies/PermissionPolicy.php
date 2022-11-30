<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy
{
    use HandlesAuthorization;

    /**
     * Check user can access to index.
     *
     * @param User $user
     * @return bool
     */
    public function index(User $user): bool
    {
        return $user->hasPermissionTo(Permission::MANAGE_PERMISSIONS)
            || $user->hasPermissionTo(Permission::CREATE_PERMISSIONS)
            || $user->hasPermissionTo(Permission::EDIT_PERMISSIONS);
    }
}
