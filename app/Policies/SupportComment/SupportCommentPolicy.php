<?php

namespace App\Policies\SupportComment;

use App\Models\SupportComment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupportCommentPolicy
{
    use HandlesAuthorization;
}
