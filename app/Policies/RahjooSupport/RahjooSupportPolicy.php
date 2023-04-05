<?php

namespace App\Policies\RahjooSupport;

use App\Models\RahjooSupport;
use App\Models\User;
use App\Services\V1\User\SupportService;
use Illuminate\Auth\Access\HandlesAuthorization;

class RahjooSupportPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param RahjooSupport $supportService
     * @return bool
     */
    public function show(User $user, RahjooSupport $supportService): bool
    {
        return $supportService->support_id == $user->id;
    }

    /**
     * @param User $user
     * @param RahjooSupport $supportService
     * @return bool
     */
    public function changeStep(User $user, RahjooSupport $supportService): bool
    {
        return $supportService->support_id == $user->id;
    }

    /**
     * @param User $user
     * @param RahjooSupport $supportService
     * @return bool
     */
    public function indexComment(User $user, RahjooSupport $supportService): bool
    {
        return $supportService->support_id == $user->id;
    }

    /**
     * @param User $user
     * @param RahjooSupport $supportService
     * @return bool
     */
    public function storeComment(User $user, RahjooSupport $supportService): bool
    {
        return $supportService->support_id == $user->id;
    }
}
