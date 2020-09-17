<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

use App\Models\Project;
use App\Models\User;

class ProjectTest extends TestCase
{
    use DatabaseMigrations;

    public function testRelationshipOneToManyExistsBetweenUserAndProjectModels()
    {
        //Given
        $userName = 'Test User';
        $projectName = 'Test Project';
        $user = User::factory()
            ->has(Project::factory()->state([
                'name' => $projectName
            ]))
            ->create();
        $project = Project::factory()
            ->for(User::factory()->state([
                'name' => $userName,
            ]))
            ->create();

        //When
        $testProjectUserName = $project->user->name;
        $testUserProjectName = $user->projects()->first()->name;

        //Then
        $this->assertEquals($userName, $testProjectUserName);
        $this->assertEquals($projectName, $testUserProjectName);
    }
}
