<?php

namespace App\Listeners;

use App\Events\PaymentRegistered;
use App\Mail\ProjectAuthorPaymentSuccessful;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailToAuthor
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
        $projectAuthor = $event->payment->donation->project->user;

        Mail::to($projectAuthor->email)->send(new ProjectAuthorPaymentSuccessful($event->payment));
    }
}
