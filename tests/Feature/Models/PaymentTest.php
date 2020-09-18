<?php

namespace Tests\Unit;

use App\Models\Donation;
use App\Models\Payment;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use DatabaseMigrations;

    public function testPaymentCorrespondsToOneDonation()
    {
        //Given
        $payment = Payment::factory()->create();
        $donation = $payment->donation;

        //Then
        $this->assertDatabaseHas('payments', ['donation_id' => $donation->id]);
    }

    public function testDonationCanHaveManyPayments()
    {
        //Given
        $donation = Donation::factory()
            ->has(Payment::factory()->count(3))
            ->create();
        $payments = $donation->payments;

        //Then
        $this->assertDatabaseCount('payments', 3);

        $payments->each(function($payment) use ($donation){
            $this->assertTrue($donation->id === $payment->donation_id);
        });
    }
}
