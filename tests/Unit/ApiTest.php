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


}