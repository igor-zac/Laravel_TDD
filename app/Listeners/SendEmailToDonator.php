<?php

namespace App\Listeners;

use App\Events\PaymentRegistered;
use App\Mail\DonatorPaymentSuccessful;
use App\Mail\ProjectAuthorPaymentSuccessful;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailToDonator
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
     * @param  PaymentRegistered  $event
     * @return void
     */
    public function handle(PaymentRegistered $event)
    {
        $donator = $event->payment->user;
        Mail::to($donator->email)->send(new DonatorPaymentSuccessful($event->payment));
    }
}
