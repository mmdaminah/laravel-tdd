@extends('layouts.app')
@section('content')
    <header class="flex items-between mb-3 py-4 w-full items-center">
        <div class="flex w-full justify-between items-end">
            <div>
                <p class="text-default text-sm font-normal">
                    <a href="/projects" class="text-default text-sm font-normal no-underline">My Projects </a>
                    / {{$project->title}}
                </p>
            </div>
            <div class="flex items-center">
                @foreach($project->members as $member)
                    <img class="rounded-full w-8 mr-2"
                         src="{{gravatar_url($member->email)}}"
                         alt="{{$member->name}}'s avatar">
                @endforeach
                <img class="rounded-full w-8 mr-2"
                     src="{{gravatar_url($project->owner->email)}}"
                     alt="{{$project->owner->name}}'s avatar">
                <a href="{{$project->path().'/edit'}}" class="button ml-4">Edit Project</a>
            </div>
        </div>
    </header>
    <main>
        <div class="lg:flex -mx-3">
            <div class="lg:w-3/4 px-3 mb-6">
                <div class="mb-8">
                    <h2 class="mb-3 text-default text-lg font-normal">Tasks</h2>
                    @foreach($project->tasks as $task)
                        <div class="card mb-3">
                            <form action="{{$task->path()}}" method="POST">
                                @method('PATCH')
                                @csrf
                                <div class="flex">
                                    <input type="text" name="body"
                                           class="w-full bg-card text-default {{$task->completed ? 'text-default':''}}"
                                           value="{{$task->body}}">
                                    <input type="checkbox"
                                           {{$task->completed ? 'checked':''}} onchange="this.form.submit()"
                                           name="completed" id="">
                                </div>
                            </form>
                        </div>
                    @endforeach
                    <form action="{{$project->path().'/tasks'}}" method="POST">
                        @csrf
                        <input name="body" class="card mb-3 w-full bg-card text-default" placeholder="Add a new task"/>
                    </form>
                </div>
                <div>
                    <h2 class="mb-3 text-default text-lg font-normal">General Notes</h2>
                    <form action="{{$project->path()}}" method="POST">
                        @method('PATCH')
                        @csrf
                        <textarea name='notes' class="card w-full min-h-[200px] mb-4"
                                  placeholder="Anything special that you want to make a note of?">{{$project->notes}}</textarea>
                        <button type="submit" class="button">Save</button>
                    </form>
                    @include('errors')

                </div>
            </div>
            <div class="lg:w-1/4 px-3">
                @include('projects.card')
                @include('projects.activity.card')

                @can('manage', $project)
                    @include('projects.invite')
                @endcan
            </div>
        </div>
    </main>
@endsection
