<?php

namespace App\Listeners\Payment;

use App\Enums\Rahjoo\RahjooSupportStep;

class ChangeStepRahjooSupport
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        $event->payment->rahjooSupport()->update([
            'step' => RahjooSupportStep::Second,
        ]);
    }
}
