<?php

namespace App\Policies\Question;

use App\Enums\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionPolicy
{
    use HandlesAuthorization;

    public function storeComment(User $user): bool
    {
        return $user->isRahyab() || $user->isRahnama();
    }
}
