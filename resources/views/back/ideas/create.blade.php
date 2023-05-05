@extends('layouts.app')

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
                    <form action="{{route('ideas-store')}}" method="post">
                        {{-- input for title of idea --}}
                        <div class="mb-3">
                            <label class="form-label">Title of the idea</label>
                            <input type="text" class="form-control" name="title" value={{old('title')}}>
                        </div>
                        {{-- input for description of idea --}}
                        <div class="mb-3">
                            <label class="form-label">Description of the idea</label>
                            <textarea class="form-control" name="description" rows="4" cols="50"></textarea>
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