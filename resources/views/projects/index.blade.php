@extends('layouts.app')
@section('content')
    <header class="flex items-between mb-3 py-4 w-full items-center">
        <div class="flex w-full justify-between items-end">
            <h2 class="text-gray-400 text-sm font-normal">My Projects</h2>
            <a href="/projects/create" class="button">New Project</a>
        </div>
    </header>

    <main class="flex flex-wrap -mx-3">
        @forelse($projects as $project)

            <div class="lg:w-1/3 px-3 pb-6">
            @include('projects.card')
            </div>
        @empty
            <div>No projects yet.</div>
        @endforelse
    </main>

@endsection
