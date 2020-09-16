<?php


namespace Tests\Feature;

use App\Models\User;
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
        $projectName = 'My First Project';
        Project::factory()->create([
            'name' => $projectName
        ]);

        //When
        $response = $this->get('/projects');

        //Then
        $response->assertSee($projectName);
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

    public function testAuthorNameAppearsOnProjectDetailPage()
    {
        //Given
        $authorName = 'Test User';
        $project = Project::factory()
            ->for(User::factory()->state([
                'name' => $authorName
            ]))
            ->create();
        $url = '/projects/'.$project->id;

        //When
        $response = $this->get($url);

        //Then
        $response->assertSee($authorName);
    }

    public function testAuthenticatedUserCanCreateAProject()
    {
        //Given
        $user = User::factory()->create();
        $projectData = [
            'name' => 'Test Project',
            'description' => 'Test Project Description'
        ];

        //When
        $projectListPage = $this->actingAs($user)->get(route('projects.index'));
        $projectCreateForm = $this->actingAs($user)->get(route('projects.create'));
        $projectCreatedRecap = $this->actingAs($user)->post(route('projects.store'), $projectData);

        //Then
        $projectListPage->assertSee('<button type="button">Creer un projet</button>', false);

        $projectCreateForm->assertViewIs('projects.create-project');

        $projectCreatedRecap->assertCreated();
        $projectCreatedRecap->assertViewIs('projects.create-project_recap');
        $projectCreatedRecap->assertSee($projectData['name']);
        $projectCreatedRecap->assertSee($projectData['description']);
        $projectCreatedRecap->assertSee($user->name);
        $projectCreatedRecap->assertSee($user->projects()->first()->created_at);
    }

    public function testNotAuthenticatedUserCannotAddProject()
    {
        //Given
        $projectListPage = $this->get(route('projects.index'));

        //Then
        $projectListPage->assertDontSee('<button type="button">Creer un projet</button>', false);
    }
}