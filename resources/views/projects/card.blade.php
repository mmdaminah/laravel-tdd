<div class="overflow-hidden card h-[200px] flex flex-col">
    <h3 class="font-normal text-xl py-4 -ml-5 border-l-4 border-blue-light pl-4 mb-2">
        <a class="text-default no-underline" href="{{$project->path()}}">
            {{$project->title}}
        </a>
    </h3>
    <div class="overflow-clip mb-4 text-default flex-1">{{$project->description}}</div>
    @can('manage', $project)
        <footer>
            <form action="{{$project->path()}}" method="POST" class="text-right">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-xs">Delete</button>
            </form>
        </footer>
    @endcan
</div>
