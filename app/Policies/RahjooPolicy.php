<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\Rahjoo;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RahjooPolicy
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
        return $user->hasPermissionTo(Permission::VIEW_RAHJOOS)
            || $user->hasPermissionTo(Permission::MANAGE_RAHJOOS);
    }

    /**
     * Check user can access to show.
     *
     * @param User $user
     * @return bool
     */
    public function show(User $user): bool
    {
        return $user->hasPermissionTo(Permission::VIEW_RAHJOOS)
            || $user->hasPermissionTo(Permission::MANAGE_RAHJOOS);
    }

    /**
     * Check user can access to create.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::STORE_RAHJOOS)
            || $user->hasPermissionTo(Permission::MANAGE_RAHJOOS);
    }

    /**
     * Check user can access to delete.
     *
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo(Permission::DELETE_RAHJOOS)
            || $user->hasPermissionTo(Permission::MANAGE_RAHJOOS);
    }

    /**
     * @param User $user
     * @param Rahjoo $rahjoo
     * @return bool
     */
    public function manageQuestionPoints(User $user, Rahjoo $rahjoo): bool
    {
        return $user->isRahyab() && $rahjoo->rahyab_id == $user->id;
    }

    /**
     * @param User $user
     * @param Rahjoo $rahjoo
     * @return bool
     */
    public function storeQuestionComment(User $user, Rahjoo $rahjoo): bool
    {
        return $user->isRahyab() && $rahjoo->rahyab_id == $user->id;
    }
}
