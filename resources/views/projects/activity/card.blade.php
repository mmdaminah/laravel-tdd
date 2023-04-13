<div class="card mt-3">
    <ul class="text-xs">
        @foreach($project->activities as $activity)
            <li class="mb-1">
                @include("projects.activity.{$activity->description}")
                <span class="text-gray-400">
                    {{$activity->created_at->diffForHumans()}}
                </span>
            </li>
        @endforeach
    </ul>
</div>
