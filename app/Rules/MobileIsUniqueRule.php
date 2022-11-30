<?php

namespace App\Rules;

use App\Repositories\V1\User\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Validation\Rule;

class MobileIsUniqueRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return resolve(UserRepositoryInterface::class)->mobileIsUnique(to_valid_mobile_number($value));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans("The :attribute isn't unique");
    }
}
