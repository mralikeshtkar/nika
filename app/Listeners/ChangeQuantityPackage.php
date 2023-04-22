<?php

namespace App\Listeners;

use App\Models\Package;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ChangeQuantityPackage
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
        $paymentable = $event->payment->paymentable;
        $paymentable->update([
            'quantity' => $paymentable->quantity--,
        ]);
    }
}
