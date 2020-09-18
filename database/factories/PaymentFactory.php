<?php

namespace Database\Factories;

use App\Models\Donation;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'donation_id' => Donation::factory(),
            'amount' => function (array $attributes) {
                $correspondingDonation = Donation::find($attributes['donation_id']);
                $amount = $this->faker->numberBetween(0, $correspondingDonation->amount);

                $correspondingDonation->validated = ($amount === $correspondingDonation->amount) ? true : false ;

                return $amount;
            }
        ];
    }
}
