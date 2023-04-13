<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_has_a_path()
    {
        $project = Project::factory()->create();

        $this->assertEquals($project->path(), '/projects/'.$project->id);
    }

    public function test_it_belongs_to_a_user()
    {
        $project = Project::factory()->create();
        $this->assertInstanceOf(User::class, $project->owner);
    }

    public function test_it_can_add_a_task()
    {
       $project = Project::factory()->create();
       $project->addTask('Some random task');
       $this->assertCount(1, $project->tasks);
    }

    public function test_it_can_invite_a_user()
    {
       $project = Project::factory()->create();
       $project->invite($user = User::factory()->create());
       $this->assertTrue($project->members->contains($user));
    }
}
