<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;

class ProjectTaskController extends Controller
{
    public function store(Project $project)
    {
        $this->authorize('update', $project);
        request()->validate(['body' => 'required']);
        $project->addTask(request('body'));
        return redirect($project->path());
    }

    public function update(Project $project, Task $task)
    {
        $this->authorize('update', $task->project);
        request()->validate(['body' => 'required']);
        $task->update([
            "body" => request('body'),
        ]);
        request('completed') ? $task->complete() : $task->inComplete();
        return redirect($project->path());
    }
}
