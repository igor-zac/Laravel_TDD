<?php

namespace Tests\Feature;

use App\Listeners\SendEmailToAuthor;
use App\Mail\DonatorPaymentSuccessful;
use App\Mail\ProjectAuthorPaymentSuccessful;
use App\Models\Donation;
use App\Models\Payment;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
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
        $user = $donation->user;

        $paymentData = ['amount' => $donation->amount];
        $paymentRoute = route('pay', ['donation' => $donation->id]);

        //When
        $this->actingAs($user)->post($paymentRoute, $paymentData);
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
        $user = $donation->user;

        $paymentData = ['amount' => $donation->amount];
        $paymentRoute = route('pay', ['donation' => $donation->id]);

        //When
        $this->actingAs($user)->post($paymentRoute, $paymentData);

        //Then
        $this->assertDatabaseCount('payments', 0);
    }

    public function testEmailSentToAuthorOnSuccessfulPayment()
    {
        Mail::fake();

        //Given
        $donation = Donation::factory()->create();
        $projectAuthor = $donation->project->user;

        //When
        Payment::factory()->create([
            'donation_id' => $donation->id
        ]);

        //Then
        Mail::assertSent(ProjectAuthorPaymentSuccessful::class, function ($mail) use ($projectAuthor) {
            return $mail->hasTo($projectAuthor->email);
        });
    }

    public function testEmailSentToDonatorOnSuccessfulPayment()
    {
        Mail::fake();

        //Given
        $donation = Donation::factory()->create();
        $donator = $donation->user;

        //When
        Payment::factory()->create([
            'donation_id' => $donation->id
        ]);

        //Then
        Mail::assertSent(DonatorPaymentSuccessful::class, function ($mail) use ($donator) {
            return $mail->hasTo($donator->email);
        });
    }

    public function testEmailNotSentToAuthorOnUnsuccessfulPayment()
    {
        Mail::fake();

        //Given
        $donation = Donation::factory()->create(['validated' => true]);
        $user = $donation->user;

        $paymentData = [
            'amount' => $donation->amount + 1
        ];
        $paymentRoute = route('pay', $donation->id);

        //When
        $this->actingAs($user)->post($paymentRoute, $paymentData);

        //Then
        Mail::assertNothingSent();
    }

    public function testEmailNotSentToDonatorOnUnsuccessfulPayment()
    {
        Mail::fake();

        //Given
        $donation = Donation::factory()->create(['validated' => true]);
        $user = $donation->user;

        $paymentData = [
            'amount' => $donation->amount + 1
        ];
        $paymentRoute = route('pay', $donation->id);

        //When
        $this->actingAs($user)->post($paymentRoute, $paymentData);

        //Then
        Mail::assertNothingSent();
    }

//    public function testEmailSentToAuthorContainsAllDonationsAndPaymentsForProject()
//    {
//
//    }
//
//    public function testEmailSentToDonatorContainsPaymentAndDonationData()
//    {
//
//    }
}
