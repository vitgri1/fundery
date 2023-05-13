@extends('layouts.front')

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
                        <form action="{{route('front-like', $idea)}}" method="post" class="mb-3">
                            <input type="hidden" name="heart_id" value="{{$user->id ?? 0}}">
                            <button type="submit" class="btn btn-outline-warning">Like</button>
                            @csrf
                            @method('put')
                        </form>
                        <form action="{{route('front-pledge', $idea)}}" method="post" class="input-group mb-3">
                            <input class="form-control" type="text" name="amount">
                            <input type="hidden" name="donator_id" value="{{$user->id ?? 0}}">
                            <button type="submit" class="btn btn-warning">Donate</button>
                            @csrf
                            @method('put')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection