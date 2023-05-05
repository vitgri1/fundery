@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="card mt-5">
                {{-- title --}}
                <div class="card-header">
                    <h1>Create a Hash-tag</h1>
                </div>
                <div class="card-body">
                    <form action="{{route('tags-store')}}" method="post">
                        {{-- input for title of tag --}}
                        <div class="mb-3">
                            <label class="form-label">Title of the Hash-tag</label>
                            <input type="text" class="form-control" name="title" value={{old('title')}}>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection