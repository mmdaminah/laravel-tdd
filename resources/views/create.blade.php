@extends("layouts.app")

@section('content')
    <form action="/projects" method="post">
        <label for="">Title</label>
        <input type="text" name="title" id="">
        <label for="">Description</label>
        <textarea name="description" id="" cols="30" rows="10"></textarea>
        <button type="submit">Create Project</button>
    </form>
@endsection
