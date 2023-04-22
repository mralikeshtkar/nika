<?php

namespace App\Enums\Order;

use BenSampo\Enum\Enum;

class OrderStatus extends Enum
{
    const Preparation = "preparation";
    const Posted = "posted";
    const Delivered = "delivered";
}
