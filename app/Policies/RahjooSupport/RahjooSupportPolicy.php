<?php

namespace App\Policies\RahjooSupport;

use App\Models\User;
use App\Services\V1\User\SupportService;
use Illuminate\Auth\Access\HandlesAuthorization;

class RahjooSupportPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param SupportService $supportService
     * @return bool
     */
    public function show(User $user, SupportService $supportService): bool
    {
        return $supportService->support_id == $user->id;
    }
}
