<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Models\Project;
use App\Models\User;


use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
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

        $projectDonationRoute = route('projects.donations.create', ["project" => $project->id]);

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
        $projectDonationRoute = route('projects.donations.create', ["project" => $project->id]);

        //Then
        $this->expectException(AuthenticationException::class);

        //When
        $this->get($projectDonationRoute);
    }

    public function testAuthenticatedUserCanCreateDonationInDB()
    {
        //Given
        $authenticatedUser = User::factory()->create();
        $project = Project::factory()->create();

        $donationAmount = 1000;
        $donationData = ['amount' => $donationAmount];

        $DonationStoreRoute = route('projects.donations.store', ["project" => $project->id]);

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

        $DonationStoreRoute = route('projects.donations.store', ["project" => $project->id]);

        //Then
        $this->expectException(AuthenticationException::class);

        //When
        $this->post($DonationStoreRoute, $donationData);
    }

    public function testAuthenticatedUserCanViewHisDonationList()
    {
        //Given
        $user = User::factory()
            ->has(Donation::factory()->count(3))
            ->create();
        $donations = $user->donations()->get()->toArray();

        $donationListRoute = route('donations.index');

        //When
        $donationListPage = $this->actingAs($user)->get($donationListRoute);

        //Then
        foreach ($donations as $donation){
            $donationListPage->assertSee($donation["id"]);
        }
    }

    public function testAuthenticatedUserCannotViewAnotherUsersDonationList()
    {
        //Given
        $donations = Donation::factory()
            ->count(3)
            ->create();
        $user = User::factory()->create();

        $donationListRoute = route('donations.index');

        //When
        $donationListPage = $this->actingAs($user)->get($donationListRoute);

        //Then
        foreach ($donations as $donation){
            $donationListPage->assertDontSee('<p>Amount: <br> '. $donation->amount .' <br/></p>');
            $donationListPage->assertDontSee('<p>Project: '. $donation->project->name .'</p>');
        }
    }

    public function testUnauthenticatedUserCannotViewDonationList()
    {
        //Given
        Donation::factory()
            ->count(3)
            ->create();

        $donationListRoute = route('donations.index');

        //Then
        $this->expectException(AuthenticationException::class);

        //When
        $this->get($donationListRoute);
    }

    public function testAuthenticatedUserCanViewDonationDetailPageForHisDonation()
    {
        //Given
        $donation = Donation::factory()->create();
        $user = $donation->user;

        $donationPageRoute = route('donations.show', ['donation' => $donation->id]);

        //When
        $detailPageForDonation = $this->actingAs($user)->get($donationPageRoute);

        //Then
        $detailPageForDonation->assertOk();
        $detailPageForDonation->assertViewIs('donations.donation-detail');
    }

    public function testAuthenticatedUserCannotViewAnotherUsersDonationDetailPage()
    {
        //Given
        $donation = Donation::factory()->create();
        $randomUser = User::factory()->create();

        $donationPageRoute = route('donations.show', ['donation' => $donation->id]);

        //Then
        $this->expectException(AuthorizationException::class);

        //When
        $this->actingAs($randomUser)->get($donationPageRoute);
    }

    public function testUnauthenticatedUserCannotViewAnotherUsersDonationDetailPage()
    {
        //Given
        $donation = Donation::factory()->create();

        $donationPageRoute = route('donations.show', ['donation' => $donation->id]);

        //Then
        $this->expectException(AuthenticationException::class);

        //When
        $this->get($donationPageRoute);
    }
}
