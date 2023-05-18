<div class="tags --tags">
    <div class="added-tags --idea--tags">
        @if(isset($tags))
        @foreach ($tags as $tag)
            <div class="tag --tag">{{$tag->title}}<i></i></div>
        @endforeach
        @endif
    </div>
    <div class="add input-group">
        <input type="text" class="form-control --add--new" data-url="{{route('front-tags-list')}}">
        <button type="button" class="btn btn-info --create--add--tag" data-url="{{route('front-add-new-tag')}}">add</button>
    </div>
    <div class="list --tags--list" data-url="{{route('front-add-tag')}}">
    </div>
    <div class="--tags--inputs">
        @if(isset($tags))
        @foreach ($tags as $tag)
            <input name="tags[]" class="--tag--input" type="hidden" value="{{$tag->title}}">
        @endforeach
        @endif
    </div>
</div>