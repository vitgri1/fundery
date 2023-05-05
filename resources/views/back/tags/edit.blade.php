@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="card mt-5">
                {{-- title --}}
                <div class="card-header">
                    <h1>Edit Hash-tag</h1>
                </div>
                <div class="card-body">
                    <form action="{{route('tags-update', $tag)}}" method="post">
                        {{-- input for title of tag --}}
                        <div class="mb-3">
                            <label class="form-label">Title of tag</label>
                            <input type="text" class="form-control" name="title" value="{{ old('title', $tag->title) }}">
                        </div>
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