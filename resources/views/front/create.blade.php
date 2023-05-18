@extends('layouts.front')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="card mt-5">
                {{-- title --}}
                <div class="card-header">
                    <h1>Suggest idea for approval</h1>
                </div>
                <div class="card-body">
                    <form action="{{route('front-store')}}" method="post" enctype="multipart/form-data">
                        {{-- input for title of idea --}}
                        <div class="mb-3">
                            <label class="form-label">Title of the idea</label>
                            <input type="text" class="form-control" name="title" value={{old('title')}}>
                        </div>
                        {{-- input for description of idea --}}
                        <div class="mb-3">
                            <label class="form-label">Description of the idea</label>
                            <textarea class="form-control" name="description" rows="4" cols="50">{{old('description')}}</textarea>
                        </div>
                        {{-- funds for the idea --}}
                        <div class="mb-3">
                            <label class="form-label">Funds you are asking</label>
                            <input type="text" class="form-control" name="funds" value={{old('funds')}}>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tags</label>
                            @include('front.tags')
                        </div>
                        {{-- Main photo --}}
                        <div class="mb-3">
                            <label class="form-label">Main Idea photo</label>
                            <input type="file" class="form-control" name="photo">
                        </div>
                        {{-- Gallery --}}
                        <div class="mb-3" data-gallery="0">
                            <label class="form-label">Gallery photo <span class="rem">X</span></label>
                            <input type="file" class="form-control">
                        </div>

                        <div class="gallery-inputs">
                        </div>

                        <button type="button" class="btn btn-secondary --add--gallery">add gallery photo</button>
                        @if(isset($user))
                        <button type="submit" class="btn btn-primary">Submit</button>
                        @else
                        <div class="btn btn-primary disabled clickable-disabled --submit--btn">Submit</div>
                        @endif
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- login modal --}}
@include('front.modal-login')
@endsection