<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NationalCodeRule implements Rule
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
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return strlen($value) == 10 && ctype_digit($value) && $this->validateNationalCode($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return trans('The :attribute is invalid');
    }

    /**
     * Check national code is valid.
     *
     * @param $code
     * @return bool
     */
    public function validateNationalCode($code): bool
    {
        $remains = collect(array_reverse(str_split(substr($code, 0, -1))))->map(function ($item, $key) {
                return $item * ($key + 2);
            })->sum() % 11;
        return $remains < 2 ? intval(substr($code, -1)) == $remains : (11 - $remains) == intval(substr($code, -1));
    }
}
