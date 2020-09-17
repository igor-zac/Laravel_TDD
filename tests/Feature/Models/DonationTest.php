<?php

namespace Tests\Unit;

use App\Models\Donation;
use App\Models\User;
use App\Models\Project;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class DonationTest extends TestCase
{
    use DatabaseMigrations;

    public function testProjectCanHaveManyDonations()
    {
        //Given
        $project = Project::factory()
            ->has(Donation::factory()->count(3))
            ->create();

        //When
        $arrayOfDonations = $project->donations()->get()->toArray();

        //Then
        $this->assertTrue( count($arrayOfDonations) > 1);
    }

    public function testUserCanDoManyDonations()
    {
        //Given
        $project = User::factory()
            ->has(Donation::factory()->count(3))
            ->create();

        //When
        $arrayOfDonations = $project->donations()->get()->toArray();

        //Then
        $this->assertTrue( count($arrayOfDonations) > 1);
    }
}
