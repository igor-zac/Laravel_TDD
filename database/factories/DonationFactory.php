<?php

namespace Database\Factories;

use App\Models\Donation;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DonationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Donation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'amount' => $this->faker->randomNumber(),
            'user_id' => User::factory(),
            'project_id' => Project::factory(),
            'validated' => $this->faker->boolean
        ];
    }
}
