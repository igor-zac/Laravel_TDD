<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Models\Payment;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PaymentTest extends TestCase
{

    use DatabaseMigrations;

    public function testDonationIsValidatedWhenPaymentAmountIsEqualToDonationAmount()
    {
        //Given
        $donation = Donation::factory()->create([
            'validated' => false
        ]);

        $paymentData = ['amount' => $donation->amount];
        $paymentRoute = route('pay',['donation' => $donation->id]);

        //When
        $this->post($paymentRoute, $paymentData);
        $donation->refresh();

        //Then
        $this->assertTrue($donation->validated);
    }

    public function testValidatedDonationCannotBePaidAgain()
    {
        //Given
        $donation = Donation::factory()->create([
            'validated' => true
        ]);

        $paymentData = ['amount' => $donation->amount];
        $paymentRoute = route('pay',['donation' => $donation->id]);

        //When
        $this->post($paymentRoute, $paymentData);

        //Then
        $this->assertDatabaseCount('payments', 0);
    }
}
