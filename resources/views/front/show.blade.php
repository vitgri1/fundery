@extends('layouts.front')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="card mt-5">
                {{-- title --}}
                <div class="card-header">
                    <h1>Idea: {{$idea->id}}</h1>
                    {{-- Like --}}
                    <form action="{{route('front-like', $idea)}}" method="post" class="mb-3">
                        <input type="hidden" name="heart_id" value="{{$user->id ?? 0}}">
                        <button type="submit" class="btn btn-outline-warning">Like</button>
                        @csrf
                        @method('put')
                    </form>
                </div>
                <div class="card-body">
                    {{-- tags --}}
                    <div class="tags">
                        @foreach($idea->tags as $tag)
                        <div class="tag">{{$tag->title}}</div>
                        @endforeach
                    </div>
                    {{-- description --}}
                    <div class="client-line">
                        {{-- Basic info --}}
                        <div class="basic-info-section">
                            <div>
                                {{$idea->title}}
                            </div>
                            <div>
                                @if ($idea->type == 1)
                                approved
                                @else
                                not approved
                                @endif
                            </div>
                            <div>
                                Hearts:  {{$idea->likes()}}
                            </div>
                            <div>
                                Description:  {{$idea->description}}
                            </div>
                        </div>
                        {{-- Gallery --}}
                        <div class="gallery section">
                            <a href="{{route('front-gallery', $idea)}}" class="btn btn-dark">
                                @if ($idea->photo)
                                <img src="{{asset('ideas-photo') .'/t_'. $idea->photo}}">
                                @else
                                <img src="{{asset('ideas-photo') .'/no.png'}}">
                                @endif
                            </a>
                        </div>
                        {{-- Funds --}}
                        <div class="funds-section">
                            <div>
                                Total amount needed: {{$idea->funds}} eur
                            </div>
                            <div>
                                Donated so far: {{$idea->totalDonated()}} eur
                            </div>
                            <div>
                                Needed to fulfill the goal: {{$idea->funds - $idea->totalDonated()}} eur
                            </div>
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
</div>
@endsection