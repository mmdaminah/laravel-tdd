<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TriggerActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_a_project_generates_activity()
    {
        $project = ProjectFactory::create();
        $this->assertCount(1, $project->activities);


        tap($project->activities->last(), function($activity) {

            $this->assertEquals('created_project', $activity->description);
            $this->assertNull($activity->changes );
        });

    }

    public function test_updating_a_project_generates_an_activity()
    {
       $project = ProjectFactory::create();
       $originalTitle = $project->title;
       $project->update(['title'=>'changed']);
       $this->assertCount(2, $project->activities);
       tap($project->activities->last(), function($activity) use ($originalTitle){

           $this->assertEquals('updated_project',$activity->description);

           $expected = [
                'before'=>['title'=>$originalTitle],
                'after'=>['title'=>'changed']
           ];
           $this->assertEquals( $expected, $activity->changes );
       });
    }

    public function test_create_a_new_task_generates_project_activity()
    {
        $project = ProjectFactory::create();
        $project->addTask("some task");
        $this->assertCount(2, $project->activities);
        $this->assertEquals('created_task', $project->activities->last()->description);
        tap($project->activities->last(), function($activity){
            $this->assertEquals('created_task', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
            $this->assertEquals('some task', $activity->subject->body);
        });
    }

    public function test_completing_a_task_generates_activity()
    {
        $project = ProjectFactory::withTasks(1)->create();
        $this->actingAs($project->owner)->patch($project->tasks[0]->path(),[
            'body'=> 'test',
            'completed' => true
        ]);
        $this->assertCount(3, $project->activities);
        $this->assertEquals('completed_task', $project->activities->last()->description);
        tap($project->activities->last(), function($activity){
            $this->assertEquals('completed_task', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
        });
    }

    public function test_incomplete_a_task_generates_activity()
    {
       $project = ProjectFactory::withTasks(1)->create();
       $this->actingAs($project->owner)->patch($project->tasks->first()->path(),[
           'body' => 'changed',
           'completed' => false
       ]);
       $this->assertCount(3, $project->activities);
       $this->assertEquals('incompleted_task', $project->activities->last()->description);
    }

    public function test_deleting_a_task_generates_activity()
    {
        $project = ProjectFactory::withTasks(1)->create();
        $project->tasks->first()->delete();
        $this->assertCount(3, $project->activities);
    }
}
