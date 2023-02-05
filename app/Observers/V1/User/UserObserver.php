<?php

namespace App\Observers\V1\User;

use App\Models\User;
use App\Repositories\V1\User\Interfaces\UserRepositoryInterface;

class UserObserver
{
    /**
     * @param User $user
     * @return void
     */
    public function created(User $user)
    {
        resolve(UserRepositoryInterface::class)->assignRahjooRole($user);
    }
}