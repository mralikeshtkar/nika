<?php

namespace App\Policies\IntelligencePoint;

use App\Enums\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IntelligencePointPolicy
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
        return $user->hasPermissionTo(Permission::VIEW_INTELLIGENCE_POINT)
            || $user->hasPermissionTo(Permission::MANAGE_INTELLIGENCE_POINT);
    }

    /**
     * Check user can access to create.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CREATE_INTELLIGENCE_POINT)
            || $user->hasPermissionTo(Permission::MANAGE_INTELLIGENCE_POINT);
    }

    /**
     * Check user can access to edit.
     *
     * @param User $user
     * @return bool
     */
    public function edit(User $user): bool
    {
        return $user->hasPermissionTo(Permission::EDIT_INTELLIGENCE_POINT)
            || $user->hasPermissionTo(Permission::MANAGE_INTELLIGENCE_POINT);
    }

    /**
     * Check user can access to delete.
     *
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo(Permission::DELETE_INTELLIGENCE_POINT)
            || $user->hasPermissionTo(Permission::MANAGE_INTELLIGENCE_POINT);
    }
}
