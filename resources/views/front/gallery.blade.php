@extends('layouts.front')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="card mt-5">
                {{-- filter and sort --}}
                <div class="card-header">
                    <h1>Gallery</h1>
                </div>
                {{-- listing --}}
                <div class="card-body">
                    <ul class="list-group">
                        @if ($main)
                        <li class="list-group-item">
                            <img src="{{asset('ideas-photo') .'/'. $main}}">
                        </li>
                        @endif
                        @forelse($gallery as $photo)
                        <li class="list-group-item">
                            <img src="{{asset('ideas-photo') .'/'. $photo->photo}}">
                        </li>
                        @empty
                        <li class="list-group-item">
                        <div class="client-line">Gallery is empty</div>
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection