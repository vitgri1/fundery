@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="card mt-5">
                {{-- filter and sort --}}
                <div class="card-header">
                    <h1>Ideas List</h1>
                    <form action="{{route('admin-index')}}" method="get">
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
                                        <a href="{{route('admin-index')}}" class="btn btn-danger">Clear</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                {{-- listing --}}
                <div class="card-body">
                    <ul class="list-group">
                        @forelse($ideas as $idea)
                        <li class="list-group-item">
                            <div class="dings-gal">
                                @include('front.tags')
                            </div>
                            <div class="client-line">
                                <div>
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
                                        Total amount needed: {{$idea->funds}} eur
                                    </div>
                                    <div>
                                        Donated so far: {{$idea->totalDonated()}} eur
                                    </div>
                                    <div>
                                        Needed to fulfill the goal: {{$idea->funds - $idea->totalDonated()}} eur
                                    </div>
                                    <div>
                                        Hearts:  {{count($idea->hearts)}}
                                    </div>
                                </div>
                                <div class="buttons">
                                    <a href="{{route('admin-show', $idea)}}" class="btn btn-info">Show</a>
                                    <a href="{{route('admin-edit', $idea)}}" class="btn btn-primary">Edit</a>
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="list-group-item">
                        <div class="client-line">No ideas was approved yet</div>
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>
            {{-- pagination and page navigation --}}
            <div class="m-2">
                {{ $ideas->links() }}
            </div>
        </div>
    </div>
</div>
@endsection