<?php

namespace App\Enums\Discount;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

class DiscountStatus extends Enum implements LocalizedEnum
{
    const Active="active";
    const Inactive="inactive";
}
