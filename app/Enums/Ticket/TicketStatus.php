<?php

namespace App\Enums\Ticket;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

class TicketStatus extends Enum implements LocalizedEnum
{
    const Open = "open";
    const Close = "close";
    const Canceled = "canceled";
}
