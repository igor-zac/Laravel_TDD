<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProjectAuthorPaymentSuccessful extends Mailable
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
        $projectAuthor = $this->payment->donation->project->user;
        $project = $this->payment->donation->project;
        $donations = $project->donations()->get();
        $lastPayment = $this->payment;

        return $this->view('emails.payments.project-author')
            ->with(compact('projectAuthor', 'project', 'donations', 'lastPayment'));
    }
}
