<?php

namespace App\Mail;

use App\Models\Donation;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DonatorPaymentSuccessful extends Mailable
{
    use Queueable, SerializesModels;

    protected $payment;

    /**
     * Create a new message instance.
     *
     * @param Payment $payment
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.payments.donator')
            ->with(['payment' => $this->payment]);
    }
}
