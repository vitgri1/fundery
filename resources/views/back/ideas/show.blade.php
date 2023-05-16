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
                        <div>
                            <ul class="list-group">
                                @forelse ($donations as $donation)
                                <li class="list-group-item">
                                    <div>
                                        {{$donation->amount}} eur
                                    </div>
                                    <div>
                                        {{$donation->created_at}} 
                                    </div>
                                    <div>
                                        {{$donation->donator()}}
                                    </div>
                                </li>
                                @empty
                                <li class="list-group-item">
                                    <div>
                                    There are no donations here yet
                                    </div>
                                </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                    {{-- buttons --}}
                    <div class="buttons">
                        <a href="{{route('admin-confirm', $idea)}}" class="btn btn-success">Confirm</a>
                        <form action="{{route('admin-delete', $idea)}}" method="post">
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