<?php

namespace App\Policies\Intelligence;

use App\Enums\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IntelligencePolicy
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
        return $user->hasPermissionTo(Permission::VIEW_INTELLIGENCES)
            || $user->hasPermissionTo(Permission::MANAGE_INTELLIGENCES);
    }

    /**
     * Check user can access to show.
     *
     * @param User $user
     * @return bool
     */
    public function show(User $user): bool
    {
        return $user->hasPermissionTo(Permission::VIEW_INTELLIGENCES)
            || $user->hasPermissionTo(Permission::MANAGE_INTELLIGENCES);
    }

    /**
     * Check user can access to create.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CREATE_INTELLIGENCES)
            || $user->hasPermissionTo(Permission::MANAGE_INTELLIGENCES);
    }

    /**
     * Check user can access to edit.
     *
     * @param User $user
     * @return bool
     */
    public function edit(User $user): bool
    {
        return $user->hasPermissionTo(Permission::EDIT_INTELLIGENCES)
            || $user->hasPermissionTo(Permission::MANAGE_INTELLIGENCES);
    }

    /**
     * Check user can access to delete.
     *
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo(Permission::DELETE_INTELLIGENCES)
            || $user->hasPermissionTo(Permission::MANAGE_INTELLIGENCES);
    }
}
