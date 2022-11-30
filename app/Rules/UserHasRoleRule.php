<?php

namespace App\Rules;

use App\Enums\Role;
use App\Models\User;
use App\Repositories\V1\User\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Validation\Rule;

class UserHasRoleRule implements Rule
{
    private mixed $roles;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(mixed $roles)
    {
        $this->roles = $roles;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return resolve(UserRepositoryInterface::class)->userHasRole($value, $this->roles);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('The :attribute is invalid');
    }
}
