<?php


namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ProjectTest extends TestCase
{

    public function testHttpSuccessStatusOnGetRequestForProjectsUrl()
    {
        //Given
        $response = $this->get('/project');

        //Then
        $response->assertOk();
    }

    public function testPresenceOfH1TagWithCorrentContent()
    {
        //Given
        $view = $this->view('project');

        //Then
        $expected = "<h1>Liste des projets</h1>";
        $view->assertSee($expected, false);
    }
}