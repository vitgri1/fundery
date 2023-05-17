@extends('layouts.front')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-7">
            <div class="card mt-5">
                {{-- title --}}
                <div class="card-header">
                    <h1>Idea: {{$idea->title}}</h1>
                    
                </div>
                <div class="card-body">
                    <div class="like-tag-line">
                        {{-- Like --}}
                        <div class="like">
                            <div class="hearts">
                                Hearts: {{$idea->likes()}}
                            </div>
                            <form action="{{route('front-like', $idea)}}" method="post">
                                <input type="hidden" name="heart_id" value="{{Auth::id() ?? 0}}">
                                <button type="submit" class="btn btn-outline-info p-0">♥</button>
                                @csrf
                                @method('put')
                            </form>
                        </div>
                        {{-- tags --}}
                        <div class="tags">
                            @foreach($idea->tags as $tag)
                            <div class="tag">{{$tag->title}}</div>
                            @endforeach
                        </div>
                    </div>
                    {{-- description --}}
                    <div class="client-line">
                        {{-- Basic info --}}
                        <div class="basic-info-section">
                            <div>
                                Description:  {{$idea->description}}
                            </div>
                        </div>
                        {{-- Gallery --}}
                        <div class="gallery-section">
                            <a href="{{route('front-gallery', $idea)}}" class="btn btn-dark">
                                @if ($idea->photo)
                                <img src="{{asset('ideas-photo') .'/t_'. $idea->photo}}">
                                @else
                                <img src="{{asset('ideas-photo') .'/no.png'}}" class="no-photo">
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card mt-5">
                <div class="row g-0">
                    <div class="col-md-10">
                        <div class="card-body">
                            <h2 class="card-title">Donate</h2>
                            {{-- Funds --}}
                            <form action="{{route('front-pledge', $idea)}}" method="post" class="input-group mb-3">
                                <input class="form-control" type="text" name="amount">
                                <input type="hidden" name="donator_id" value="{{Auth::id() ?? 0}}">
                                <button type="submit" class="btn btn-warning">Donate</button>
                                @csrf
                                @method('put')
                            </form>
                            @if ($idea->funds - $idea->totalDonated() <= 0)
                            <div class="funds-section">Requested funds collected</div>
                            @else
                            <div class="funds-section">
                                <div>
                                    Needed: <span>{{$idea->funds}}€ </span>
                                </div>
                                <div>
                                    Donated so far: <span>{{$idea->totalDonated()}}€</span>
                                </div>
                                <div>
                                    Needed to fulfill the goal: <span>{{$idea->funds - $idea->totalDonated()}}€ </span> 
                                </div>
                                <ul class="list-group">
                                    <label>Donators:</label>
                                    @forelse ($donations as $donation)
                                    <li class="list-group-item donator">
                                        <div>{{substr($donation->created_at, 0, 10)}} </div>
                                        <div>{{$donation->amount}}€</div>
                                        <div>{{$donation->donator()}}</div>
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
                            @endif
                        </div>
                    </div>
                    <div class="vertical-progress-bar card-body col-md-2 p-2">
                        <div class="vertical-progress-bar-color"
                        @if($idea->funds - $idea->totalDonated() > 0)
                        style="height:{{100 * $idea->totalDonated() / $idea->funds}}%;" 
                        @endif></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection