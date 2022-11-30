<?php

namespace App\Services\V1\User;

class VerificationCodeService
{
    /**
     * Generate verification code.
     *
     * @return int
     */
    public function generate(): int
    {
        //todo set number length from setting.
        return $this->_randomNumber(5);
    }

    /**
     * Generate random number.
     *
     * @param int $length
     * @return int
     */
    private function _randomNumber(int $length = 6): int
    {
        return mt_rand(pow(10, $length - 1), pow(10, $length) - 1);
    }
}
