<?php

namespace App\Repositories\V1\User\Eloquent;

use App\Models\User;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\User\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use JetBrains\PhpStorm\Pure;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * UserRepository constructor.
     *
     * @param User $model
     */
    #[Pure] public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * Find or create user by mobile.
     *
     * @param mixed $mobile
     * @return Builder|Model
     */
    public function firstOrCreateByMobile(mixed $mobile): Model|Builder
    {
        return $this->model->firstOrCreate(['mobile' => $mobile], ['mobile' => $mobile]);
    }

    /**
     * Find user by mobile.
     *
     * @param mixed $mobile
     * @return Model|Builder|null
     */
    public function findByMobile(mixed $mobile): Model|Builder|null
    {
        return $this->model->where('mobile', $mobile)->first();
    }

    /**
     * @param $id
     * @return Model|array|Collection|Builder|null
     */
    public function findOrFailByIdHasPersonnel($id): Model|array|Collection|Builder|null
    {
        return $this->model->withWhereHas('personnel')->findOrFail($id);
    }

    /**
     * @param $id
     * @return Model|array|Collection|Builder|null
     */
    public function findOrFailByIdWithPersonnel($id): Model|array|Collection|Builder|null
    {
        return $this->model->with('personnel')->findOrFail($id);
    }

    /**
     * Check user has role.
     *
     * @param $user
     * @param $roles
     * @return mixed
     */
    public function userHasRole($user, $roles): mixed
    {
        return User::query()->where('id',$user)->role($roles)->exists();
    }

    /**
     * Update verification code and verification code requested time.
     *
     * @param $user
     * @param int $code
     * @return int
     */
    public function updateVerificationCode($user, int $code): int
    {
        //todo set verification code requested at added time from setting
        $user->update([
            'verification_code' => $code,
            'verification_code_expired_at' => now()->addMinutes(2),
        ]);
        return $code;
    }

    /**
     * Check mobile is unique.
     *
     * @param $mobile
     * @return bool
     */
    public function mobileIsUnique($mobile): bool
    {
        return User::query()->whereNotNull('verified_at')
            ->where('mobile', $mobile)
            ->doesntExist();
    }

    /**
     * Update or create a user.
     *
     * @param array $attributes
     * @param array $values
     * @return Builder|Model
     */
    public function updateOrCreate(array $attributes, array $values): Model|Builder
    {
        return $this->model->updateOrCreate($attributes, $values);
    }

    /**
     * Add verified condition.
     *
     * @return $this
     */
    public function verified(): static
    {
        $this->model = $this->model->whereNull('verified_at');
        return $this;
    }

    /**
     * Update user mark a verified.
     *
     * @param $user
     * @return void
     */
    public function markAsVerified($user)
    {
        $user->update([
            'verified_at' => now(),
            'verification_code' => null,
        ]);
    }

}
