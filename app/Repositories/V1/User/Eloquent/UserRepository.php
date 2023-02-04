<?php

namespace App\Repositories\V1\User\Eloquent;

use App\Enums\Role;
use App\Models\User;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Rahjoo\Interfaces\RahjooRepositoryInterface;
use App\Repositories\V1\RahjooCourse\Interfaces\RahjooCourseRepositoryInterface;
use App\Repositories\V1\User\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
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
     * @param Request $request
     * @return $this
     */
    public function filterPagination(Request $request): static
    {
        $this->searchName($request)
            ->searchMobile($request)
            ->searchRole($request)
            ->searchNotionalCode($request);
        return $this;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function searchRole(Request $request): static
    {
        $this->model = $this->model->when($request->filled('role'), function (Builder $builder) use ($request) {
            $builder->whereHas('roles', function (Builder $builder) use ($request) {
                $builder->where('name', $request->role);
            });
        });
        return $this;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function searchMobile(Request $request): static
    {
        $this->model = $this->model->when($request->filled('mobile'), function (Builder $builder) use ($request) {
            $builder->where('mobile', 'LIKE', '%' . $request->mobile . '%');
        });
        return $this;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function searchNotionalCode(Request $request): static
    {
        $this->model = $this->model->when($request->filled('national_code'), function (Builder $builder) use ($request) {
            $builder->where('national_code', 'LIKE', '%' . $request->national_code . '%');
        });
        return $this;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function searchName(Request $request): static
    {
        $this->model = $this->model->when($request->filled('name'), function (Builder $builder) use ($request) {
            $builder->where(function (Builder $builder) use ($request) {
                $builder->where('first_name', 'LIKE', '%' . $request->name . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $request->name . '%');
            });
        });
        return $this;
    }

    /**
     * @param $role
     * @return $this
     */
    public function hasRole($role): static
    {
        $this->model = $this->model->whereHas('roles', function (Builder $builder) use ($role) {
            $builder->where('name', $role);
        });
        return $this;
    }

    public function assignRole($user, $role)
    {
        return $user->syncRoles($role);
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

    public function update($model, array $attributes)
    {
        parent::update($model, $attributes);
        return $model->refresh();
    }

    /**
     * @param $user
     * @return mixed
     */
    public function logout($user): mixed
    {
        return $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
    }

    public function assignRahjooRole($user)
    {
        if ($user->wasRecentlyCreated) {
            dd("حم");
            $user->syncRoles(Role::RAHJOO);
            resolve(RahjooRepositoryInterface::class)->updateOrCreate([
                'user_id' => $user->id,
            ], [
                'user_id' => $user->id,
            ]);
        }
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
        return User::query()->where('id', $user)->role($roles)->exists();
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
