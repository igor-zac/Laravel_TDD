<?php


namespace Tests\Unit;

use Tests\TestCase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\Models\Project;
use App\Models\User;

class ApiTest extends TestCase
{
    use DatabaseMigrations;

    public function testProjectNamePresentInProjectListApiCall()
    {
        //Given
        $projectName = 'Test Project';
        $apiEndpoint = route('api.projects.index');
        Project::factory()->create([
            'name' => $projectName
        ]);

        //When
        $response = $this->getJson($apiEndpoint);

        //Then
        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => $projectName
            ]);
    }

    public function testProjectNamePresentInProjectDetailApiCall()
    {
        //Given
        $projectName = 'Test Project';
        $project = Project::factory()->create([
            'name' => $projectName
        ]);
        $apiEndpoint = route('api.projects.show', ['project' => $project->id]);

        //When
        $response = $this->getJson($apiEndpoint);

        //Then
        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => $projectName
            ]);
    }

    public function testProjectAuthorNamePresentInProjectDetailApiCall()
    {
        //Given
        $authorName = 'John Doe';
        $project = Project::factory()
            ->for(User::factory()->state([
                'name' => $authorName
            ]))
            ->create();
        $apiEndpoint = route('api.projects.show', ['project' => $project->id]);

        //When
        $response = $this->getJson($apiEndpoint);

        //Then
        $response->assertStatus(200)
            ->assertJsonFragment([
                'author' => $authorName
            ]);
    }
}