<div class="overflow-hidden card flex flex-col mt-3">
    <h3 class="font-normal text-xl py-4 -ml-5 border-l-4 border-blue-light pl-4 mb-2">
        Invite a User
    </h3>
    <div class="overflow-clip mb-4 text-gray-400 flex-1">{{$project->description}}</div>
    <form action="{{$project->path().'/invitations'}}" method="POST">
        @csrf
        @method('DELETE')
        <div class="mb-3">
            <input type="email" name="email"
                   class="border border-gray-400 rounded w-full py-2 px-3"
                   placeholder="Email Address"
            >
        </div>
        <button type="submit" class="button">Invite</button>
    </form>
    @include('errors',['bag'=>'invitations'])
</div>
