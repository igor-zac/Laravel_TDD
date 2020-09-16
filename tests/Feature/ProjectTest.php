<?php


namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

use App\Models\Project;

class ProjectTest extends TestCase
{

    use DatabaseMigrations;

    public function testHttpSuccessStatusOnGetRequestForProjectsUrl()
    {
        //Given
        $response = $this->get('/projects');

        //Then
        $response->assertOk();
    }

    public function testPresenceOfH1TagWithCorrentContent()
    {
        //Given
        $view = $this->view('projects.project-list');

        //Then
        $expected = "<h1>Liste des projets</h1>";
        $view->assertSee($expected, false);
    }

    public function testProjectNameAppearsOnTheProjectListPage()
    {
        //Given
        Project::factory()->create([
            'name' => 'My First Project'
        ]);

        //When
        $response = $this->get('/projects');

        //Then
        $response->assertSee('My First Project');
    }

    public function testProjectDetailPageIsAccessibleUsingProjectId()
    {
        //Given
        $project = Project::factory()->create();
        $url = '/projects/'.$project->id;

        //When
        $response = $this->get($url);

        //Then
        $response->assertOk();
    }

    public function testProjectNameAppearsOnTheProjectDetailPage()
    {
        //Given
        $projectName = 'My First Project';
        $project = Project::factory()->create([
            'name' => $projectName
        ]);
        $url = '/projects/'.$project->id;

        //When
        $response = $this->get($url);

        //Then
        $response->assertSee($projectName);
    }
}