<?php

namespace App\Repositories\V1\User\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterface
{
    public function firstOrCreateByMobile(mixed $mobile): Model|Builder;

    public function findByMobile(mixed $mobile): Model|Builder|null;

    public function updateVerificationCode($user, int $code): int;

    public function markAsVerified($user);

}
