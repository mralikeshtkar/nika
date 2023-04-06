<?php

namespace App\Enums\Payment;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

class PaymentStatus extends Enum implements LocalizedEnum
{
    const Pending = "pending";
    const Canceled = "canceled";
    const Success = "success";
    const Fail = "fail";
}
