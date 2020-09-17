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
        $url = route('projects.index');

        //When
        $response = $this->get($url);

        //Then
        $response->assertOk();
    }

    public function testPresenceOfH1TagWithCorrectContent()
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
        $url = route('projects.index');
        $projectName = 'My First Project';
        Project::factory()->create([
            'name' => $projectName
        ]);

        //When
        $response = $this->get($url);

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

    public function testAuthenticatedUserCanViewCreateProjectButton()
    {
        //Given
        $user = User::factory()->create();

        //When
        $projectListPage = $this->actingAs($user)->get(route('projects.index'));

        //Then
        $projectListPage->assertSee('<button type="button">Creer un projet</button>', false);

    }

    public function testAuthenticatedUserCanAccessProjectCreationForm()
    {
        //Given
        $user = User::factory()->create();

        //When
        $projectCreateForm = $this->actingAs($user)->get(route('projects.create'));

        //Then
        $projectCreateForm->assertViewIs('projects.create-project');
    }

    public function testAuthenticatedUserCanCreateNewProjectAndViewRecap()
    {
        //Given
        $user = User::factory()->create();
        $projectData = [
            'name' => 'Test Project',
            'description' => 'Test Project Description'
        ];

        //When
        $projectCreatedRecap = $this->actingAs($user)->post(route('projects.store'), $projectData);

        //Then
        $projectCreatedRecap->assertCreated();
        $projectCreatedRecap->assertViewIs('projects.create-project_recap');
        $projectCreatedRecap->assertSee($projectData['name']);
        $projectCreatedRecap->assertSee($projectData['description']);
        $projectCreatedRecap->assertSee($user->name);
        $projectCreatedRecap->assertSee($user->projects()->first()->created_at);
    }

    public function testUnauthenticatedUserCannotAddProject()
    {
        //Given
        $url = route('projects.index');

        //When
        $projectListPage = $this->get($url);

        //Then
        $projectListPage->assertDontSee('<button type="button">Creer un projet</button>', false);
    }

    public function testUnauthenticatedUserCannotAccessProjectCreationForm()
    {
        //Given
        $url = route('projects.create');

        //When
        $response = $this->get($url);

        //Then
        $response->assertViewIs('projects.project-list');
    }

    public function testOnlyProjectAuthorCanViewEditButtonOnProject()
    {
        //Given
        $project = Project::factory()
            ->create();
        $projectAuthor = $project->user;
        $anotherUser = User::factory()->create();

        $editButtonStr = '<button type="button">Edit</button>';
        $projectRoute = route('projects.show', ['project' => $project->id]);

        //When
        $projectDetailForAuthor = $this->actingAs($projectAuthor)->get($projectRoute);
        $projetDetailForOtherUser = $this->actingAs($anotherUser)->get($projectRoute);
        $projectDetailForUnauthenticatedUser = $this->get($projectRoute);

        //Then
        $projectDetailForAuthor->assertSee($editButtonStr, false);
        $projetDetailForOtherUser->assertDontSee($editButtonStr, false);
        $projectDetailForUnauthenticatedUser->assertDontSee($editButtonStr, false);
    }

    public function testOnlyProjectAuthorCanAccessProjectEditPage()
    {
        //Given
        $project = Project::factory()
            ->create();
        $projectAuthor = $project->user;
        $anotherUser = User::factory()->create();

        $projectEditRoute = route('projects.edit', ['project' => $project->id]);

        //When
        $projectDetailForAuthor = $this->actingAs($projectAuthor)->get($projectEditRoute);
        $projetDetailForOtherUser = $this->actingAs($anotherUser)->get($projectEditRoute);
        $projectDetailForUnauthenticatedUser = $this->get($projectEditRoute);

        //Then
        $projectDetailForAuthor->assertViewIs('projects.edit-project');
        $projetDetailForOtherUser->assertViewIs('projects.project-detail');
        $projectDetailForUnauthenticatedUser->assertViewIs('projects.project-detail');
    }

}