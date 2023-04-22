<?php

namespace App\Enums\Order;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

class OrderStatus extends Enum implements LocalizedEnum
{
    const Preparation = "preparation";
    const Posted = "posted";
    const Delivered = "delivered";
}
