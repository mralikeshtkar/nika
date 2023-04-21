<?php

namespace App\Listeners\Payment;

use App\Enums\Rahjoo\RahjooSupportStep;
use App\Events\Payment\PaymentWasSuccessful;

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
        dd($event->payment);
        if ($event->payment->rahjooSupport){
            $event->payment->rahjooSupport->update([
                'step' => RahjooSupportStep::Second,
            ]);
        }
    }
}
