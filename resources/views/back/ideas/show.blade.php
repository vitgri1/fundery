@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="card mt-5">
                {{-- title --}}
                <div class="card-header">
                    <h1>Idea: {{$idea->id}}</h1>
                </div>
                <div class="card-body">
                    {{-- description --}}
                    <div>
                        <div>
                            {{$idea->title}}
                        </div>
                        <div>
                            {{$idea->description}}
                        </div>
                    </div>
                    {{-- buttons --}}
                    <div class="buttons">
                        <a href="{{route('ideas-confirm', $idea)}}" class="btn btn-success">Confirm</a>
                        <form action="{{route('ideas-delete', $idea)}}" method="post">
                            <button type="submit" class="btn btn-danger">Delete</button>
                            @csrf
                            @method('delete')
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection