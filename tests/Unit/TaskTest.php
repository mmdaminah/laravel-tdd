<?php

namespace Tests\Unit;

use App\Models\Project;
use Database\Factories\TaskFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_belongs_to_a_project()
    {
        $task = Task::factory()->create();
        $this->assertInstanceOf(Project::class, $task->project);
    }

    public function test_it_has_a_path()
    {
        $task = Task::factory()->create();
        $this->assertEquals('/projects/' . $task->project->id . '/tasks/' . $task->id, $task->path());
    }

    public function test_a_task_can_be_completed()
    {
        $task = Task::factory()->create();
        $task->complete();
        $this->assertTrue($task->fresh()->completed);
    }

    public function test_a_task_can_be_incompleted()
    {
       $task = Task::factory()->create();
       $task->inComplete();
       $this->assertFalse($task->fresh()->completed);
    }
}
