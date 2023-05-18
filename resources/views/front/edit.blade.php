@extends('layouts.front')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="card mt-5">
                {{-- title --}}
                <div class="card-header">
                    <h1>Edit Idea</h1>
                </div>
                <div class="card-body">
                    <form action="{{route('front-update', $idea)}}" method="post" enctype="multipart/form-data">
                        {{-- input for title of idea --}}
                        <div class="mb-3">
                            <label class="form-label">Title of the idea</label>
                            <input type="text" class="form-control" name="title" value="{{ old('title', $idea->title) }}">
                        </div>
                        {{-- input for description of idea --}}
                        <div class="mb-3">
                            <label class="form-label">Description of the idea</label>
                            <textarea class="form-control" name="description" rows="4" cols="50">{{ old('description', $idea->description) }}</textarea>
                        </div>

                        {{-- funds for the idea --}}
                        <div class="mb-3">
                            <label class="form-label">Funds you are asking</label>
                            <input type="text" class="form-control" name="funds" value={{old('funds', $idea->funds)}}>
                        </div>
                        {{-- tags --}}
                        <div class="mb-3 --edit--tags">
                            <label class="form-label">Tags</label>
                            @include('front.tags')
                        </div>
                        {{-- photos --}}
                        <div class="mb-3">
                            <div class="container">
                                <div class="row">
                                    <div class="col-4">
                                        @if($idea->photo)
                                        <img src="{{asset('ideas-photo') .'/t_'. $idea->photo}}">
                                        @else
                                        <img src="{{asset('ideas-photo') .'/no.png'}}">
                                        @endif
                                    </div>
                                    <div class="col-8">
                                        <label class="form-label">Main Idea photo</label>
                                        <input type="file" class="form-control" name="photo">
                                        <button type="submit" name="delete" value="1" class="mt-2 btn btn-danger">Delete photo</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3" data-gallery="0">
                            <label class="form-label">Gallery photo <span class="rem">X</span></label>
                            <input type="file" class="form-control">
                        </div>

                        <div class="gallery-inputs">
                        
                        </div>

                        <button type="button" class="btn btn-secondary --add--gallery">add gallery photo</button>
                        {{-- Submit form --}}
                        <button type="submit" class="btn btn-primary">Submit</button>
                        @csrf
                        @method('put')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection