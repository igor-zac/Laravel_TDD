<?php

namespace Tests\Unit;

use App\Models\Donation;
use App\Models\User;
use App\Models\Project;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\TestCase;

class DonationTest extends TestCase
{
    use DatabaseMigrations;

    public function testProjectCanHaveManyDonations()
    {
        //Given
        $project = Project::factory()->create();

        //Then
        $this->assertTrue(count($project->donations()->toArray()) > 1);
    }

    public function testUserCanDoManyDonations()
    {

    }
}
