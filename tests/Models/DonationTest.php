<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Models\Project;
use App\Models\User;


use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class DonationTest extends TestCase
{

    use DatabaseMigrations;

    public function testAuthenticatedUserCanViewDonationCreationButton()
    {
        //Given
        $authenticatedUser = User::factory()->create();
        $project = Project::factory()->create();

        $projectPageRoute = route('projects.show', ["project" => $project->id]);

        $donationCreateButton = '<button type="button">Donate</button>';

        //When
        $projectPage = $this->actingAs($authenticatedUser)->get($projectPageRoute);

        //Then
        $projectPage->assertSee($donationCreateButton, false);
    }

    public function testUnauthenticatedUserCannotViewDonationCreationButton()
    {
        //Given
        $project = Project::factory()->create();

        $projectPageRoute = route('projects.show', ["project" => $project->id]);

        $donationCreateButton = '<button type="button">Donate</button>';

        //When
        $projectPage = $this->get($projectPageRoute);

        //Then
        $projectPage->assertDontSee($donationCreateButton);
    }

    public function testAuthenticatedUserCanAccessDonationCreationPage()
    {
        //Given
        $authenticatedUser = User::factory()->create();
        $project = Project::factory()->create();

        $projectDonationRoute = route('donations.create', ["project" => $project->id]);

        //When
        $projectPage = $this->actingAs($authenticatedUser)->get($projectDonationRoute);

        //Then
        $projectPage->assertViewIs('donations.create-donation');
    }

    public function testUnauthenticatedUserCannotAccessDonationCreationPage()
    {
        //Given
        $project = Project::factory()->create();

        $projectRoute = route('projects.show', ["project" => $project->id]);
        $projectDonationRoute = route('donations.create', ["project" => $project->id]);

        //When
        $projectPage = $this->get($projectDonationRoute);

        //Then
        $projectPage->assertRedirect($projectRoute);
    }

    public function testAuthenticatedUserCanCreateDonationInDB()
    {
        //Given
        $authenticatedUser = User::factory()->create();
        $project = Project::factory()->create();

        $donationAmount = 1000;
        $donationData = ['amount' => $donationAmount];

        $DonationStoreRoute = route('donations.store', ["project" => $project->id]);

        //When
        $donationRecap = $this->actingAs($authenticatedUser)->post($DonationStoreRoute, $donationData);

        //Then
        $donationRecap->assertCreated();
        $this->assertDatabaseCount('donations', 1);
        $this->assertDatabaseHas('donations', [
            'amount' => $donationAmount,
            'user_id' => $authenticatedUser->id,
            'project_id' => $project->id
        ]);
    }

    public function testUnauthenticatedUserCannotCreateDonationInDB()
    {
        //Given
        $project = Project::factory()->create();

        $donationAmount = 1000;
        $donationData = ['amount' => $donationAmount];

        $DonationStoreRoute = route('donations.store', ["project" => $project->id]);

        //When
        $this->post($DonationStoreRoute, $donationData);

        //Then
        $this->assertDatabaseMissing('donations', [
            'amount' => $donationAmount,
            'project_id' => $project->id
        ]);
    }
}
