<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;

class ProjectController extends Controller
{
    public function store()
    {
        // validate
        $attributes = request()->validate([
            'title' => 'required',
            'description' => 'required',
            'notes' => 'min:3'
            ]
        );
        if(!auth()->check())
            return redirect('login');
        $project = auth()->user()->projects()->create($attributes);
        // redirect
        return redirect($project->path());
    }

    public function index()
    {
        $projects = auth()->user()->accessibleProjects();
        return view('projects.index', compact('projects'));
    }

    public function show(Project $project)
    {

        $this->authorize('update', $project);
        return view('projects.show', compact('project'));
    }

    public function create()
    {
       return view('projects.create') ;
    }

    public function update(UpdateProjectRequest $updateProjectRequest,Project $project)
    {
       $project->update($updateProjectRequest->validated());
       return redirect($project->path());
    }

    public function edit(Project $project)
    {
       return view('projects.edit',compact('project'));
    }

    public function destroy(Project $project)
    {
        $this->authorize('manage', $project);
        $project->delete();
        return redirect('/projects');
    }

}
