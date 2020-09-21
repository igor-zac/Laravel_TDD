<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param Donation $donation
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function pay(Request $request, Donation $donation)
    {
        $request->validate([
            'amount' => 'required|max:' . $donation->amount
        ]);

        if($donation->validated){
            return 'Donation already paid';
        }

        $payment = Payment::create([
            'amount' => $request->input('amount'),
            'user_id' => Auth::id(),
            'donation_id' => $donation->id
        ]);

        if ($payment->amount === $donation->amount) {
            $donation->validated = true;
            $donation->save();
        }

        return 'Payment Successful';

    }
}
