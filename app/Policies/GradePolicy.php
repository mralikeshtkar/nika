<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GradePolicy
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
        return $user->hasPermissionTo(Permission::VIEW_GRADES)
            || $user->hasPermissionTo(Permission::MANAGE_GRADES);
    }

    /**
     * Check user can access to create.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CREATE_GRADES)
            || $user->hasPermissionTo(Permission::MANAGE_GRADES);
    }

    /**
     * Check user can access to edit.
     *
     * @param User $user
     * @return bool
     */
    public function edit(User $user): bool
    {
        return $user->hasPermissionTo(Permission::EDIT_GRADES)
            || $user->hasPermissionTo(Permission::MANAGE_GRADES);
    }

    /**
     * Check user can access to delete.
     *
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo(Permission::DELETE_GRADES)
            || $user->hasPermissionTo(Permission::MANAGE_GRADES);
    }
}
