<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\Address;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AddressPolicy
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
        return $user->hasPermissionTo(Permission::VIEW_ADDRESSES)
            || $user->hasPermissionTo(Permission::MANAGE_ADDRESSES);
    }

    /**
     * Check user can access to create.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CREATE_ADDRESSES)
            || $user->hasPermissionTo(Permission::MANAGE_ADDRESSES);
    }

    /**
     * Check user can access to edit.
     *
     * @param User $user
     * @param Address $address
     * @return bool
     */
    public function edit(User $user,Address $address): bool
    {
        return $user->hasPermissionTo(Permission::EDIT_ADDRESSES)
            || $user->hasPermissionTo(Permission::MANAGE_ADDRESSES)
            || $user->id == $address->user_id;
    }

    /**
     * Check user can access to delete.
     *
     * @param User $user
     * @param Address $address
     * @return bool
     */
    public function delete(User $user,Address $address): bool
    {
        return $user->hasPermissionTo(Permission::DELETE_ADDRESSES)
            || $user->hasPermissionTo(Permission::MANAGE_ADDRESSES)
            || $user->id == $address->user_id;
    }
}
