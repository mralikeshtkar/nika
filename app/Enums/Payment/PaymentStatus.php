<?php

namespace App\Enums\Payment;

use BenSampo\Enum\Enum;

class PaymentStatus extends Enum
{
    const Pending = "pending";
    const Canceled = "canceled";
    const Success = "success";
    const Fail = "fail";
}
