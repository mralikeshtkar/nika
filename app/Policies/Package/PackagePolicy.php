<?php

namespace App\Policies\Package;

use App\Enums\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PackagePolicy
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
        return $user->hasPermissionTo(Permission::VIEW_PACKAGES)
            || $user->hasPermissionTo(Permission::MANAGE_PACKAGES);
    }

    /**
     * Check user can access to show.
     *
     * @param User $user
     * @return bool
     */
    public function show(User $user): bool
    {
        return $user->hasPermissionTo(Permission::SHOW_PACKAGES)
            || $user->hasPermissionTo(Permission::MANAGE_PACKAGES);
    }

    /**
     * Check user can access to create.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CREATE_PACKAGES)
            || $user->hasPermissionTo(Permission::MANAGE_PACKAGES);
    }

    /**
     * Check user can access to edit.
     *
     * @param User $user
     * @return bool
     */
    public function edit(User $user): bool
    {
        return $user->hasPermissionTo(Permission::EDIT_PACKAGES)
            || $user->hasPermissionTo(Permission::MANAGE_PACKAGES);
    }

    /**
     * Check user can access to delete.
     *
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo(Permission::DELETE_PACKAGES)
            || $user->hasPermissionTo(Permission::MANAGE_PACKAGES);
    }
}
