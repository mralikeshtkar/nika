<?php

namespace App\Enums\Product;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

class ProductStatus extends Enum implements LocalizedEnum
{
    const Active = "active";
    const Inactive = "inactive";
}
