@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="card mt-5">
                {{-- filter and sort --}}
                <div class="card-header">
                    <h1>Hash-tags List</h1>
                    <form action="{{route('tags-index')}}" method="get">
                        <div class="container">
                            <div class="row">
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label class="form-label">Sort</label>
                                        <select class="form-select" name="sort">
                                            @foreach($sortSelect as $value => $text)
                                            <option value="{{$value}}" @if($value===$sort) selected @endif>{{$text}}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-text">Sort preferences</div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label class="form-label">Filter</label>
                                        <select class="form-select" name="filter">
                                            @foreach($filterSelect as $value => $text)
                                            <option value="{{$value}}" @if($value===$filter) selected @endif>{{$text}}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-text">Filter preferences</div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label class="form-label">Results per page</label>
                                        <select class="form-select" name="per">
                                            @foreach($perSelect as $value => $text)
                                            <option value="{{$value}}" @if($value===$per) selected @endif>{{$text}}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-text">View preferences</div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="sort-filter-buttons">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="{{route('tags-index')}}" class="btn btn-danger">Clear</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                {{-- listing --}}
                <div class="card-body">
                    <ul class="list-group">
                        @forelse($tags as $tag)
                        <li class="list-group-item">
                            <div class="client-line">
                                <div>
                                    {{$tag->title}}
                                </div>
                                <div class="buttons">
                                    <a href="{{route('tags-edit', $tag)}}" class="btn btn-primary">Edit</a>
                                    <form action="{{route('tags-delete', $tag)}}" method="post">
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                        @csrf
                                        @method('delete')
                                    </form>
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="list-group-item">
                        <div class="client-line">There are no Hash-tags</div>
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>
            {{-- pagination and page navigation --}}
            <div class="m-2">
                {{ $tags->links() }}
            </div>
        </div>
    </div>
</div>
@endsection