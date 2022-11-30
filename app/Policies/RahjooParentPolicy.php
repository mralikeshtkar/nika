<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RahjooParentPolicy
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
        return $user->hasPermissionTo(Permission::VIEW_RAHJOO_PARENTS)
            || $user->hasPermissionTo(Permission::MANAGE_RAHJOO_PARENTS);
    }

    /**
     * Check user can access to create.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CREATE_RAHJOO_PARENTS)
            || $user->hasPermissionTo(Permission::MANAGE_RAHJOO_PARENTS);
    }

    /**
     * Check user can access to delete.
     *
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo(Permission::DELETE_RAHJOO_PARENTS)
            || $user->hasPermissionTo(Permission::MANAGE_RAHJOO_PARENTS);
    }
}
