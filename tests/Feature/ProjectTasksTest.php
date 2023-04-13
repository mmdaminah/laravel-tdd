<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_add_tasks_to_projects()
    {
        $project = Project::factory()->create();
        $this->post($project->path() . '/tasks')->assertRedirect('login');
    }

    public function test_only_owner_of_a_project_may_add_tasks()
    {
        $this->signIn();
        $project = Project::factory()->create();
        $this->post($project->path() . '/tasks', ['body' => 'lorem ipsum'])
            ->assertStatus(403);
        $this->assertDatabaseMissing('tasks', ['body' => 'lorem ipsum']);
    }

    public function test_a_project_can_have_tasks()
    {
        $this->signIn();

        $project = Project::factory()->create(['owner_id' => auth()->id()]);

        $this->post($project->path() . '/tasks', ['body' => 'lorem ipsum']);

        $this->get($project->path())->assertSee('lorem ipsum');
    }

    public function test_a_task_requires_a_body()
    {
        $this->signIn();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);
        $task = Task::factory()->raw(['body' => '']);
        $this->post($project->path() . '/tasks', $task)
            ->assertSessionHasErrors('body');
    }

    public function test_a_task_can_be_updated()
    {
        $this->signIn();
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();
//        $project = Project::factory()->create(['owner_id' => auth()->id()]);
//        $task = $project->addTask('new TaskTest');
        $this->patch($project->tasks->first()->path(), [
            'body' => 'task changed',
        ]);
        $this->assertDatabaseHas('tasks', ['body' => 'task changed']);
    }

    public function test_a_task_can_be_completed()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();
        $this->patch($project->tasks->last()->path(),['body'=>'changed','completed'=>true]);
        $this->assertDatabaseHas('tasks', ['body' => 'changed', 'completed'=>true]);
    }

    public function test_a_task_can_be_incomplete()
    {
       $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();
       $this->patch($project->tasks->last()->path(), ['body'=>'changed','completed'=>false]);
       $this->assertDatabaseHas('tasks', ['body' => 'changed', 'completed'=>false]);
    }

    public function test_only_owner_of_project_should_update_task()
    {
        $this->signIn();
        $project = Project::factory()->create();
        $task = $project->addTask('test task');
        $this->patch($task->path(),
            ['body' => "changed body"]
        )->assertStatus(403);
        $this->assertDatabaseMissing('tasks',['body' => "changed body"]);
    }

}
