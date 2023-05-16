<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use App\Models\Donation;
use App\Models\Tag;
use App\Models\Photo;
use App\Models\IdeaTag;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Validator;

class IdeaController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->sort ?? '';
        $filter = $request->filter ?? '';
        $per = (int) ($request->per ?? 10);
        $page = $request->page ?? 1;

        $ideas = match($filter) {
            default => Idea::where('id', '>', 0),
        };

        $ideas = match($sort) {
            'title_asc' => $ideas->orderBy('title'),
            'title_desc' => $ideas->orderBy('title', 'desc'),
            default => $ideas->orderBy('title')
        };

        $ideas = $ideas->paginate($per)->withQueryString();

        $ideas->map(function($i) {
            // TAGS
            $tagsId = $i->ideaTag->pluck('tag_id')->all();
            $tags = Tag::whereIn('id', $tagsId)->get();
            $i->tags = $tags;
        });

        return view('back.ideas.index', [
            'ideas' => $ideas,
            'sortSelect' => Idea::SORT,
            'sort' => $sort,
            'filterSelect' => Idea::FILTER,
            'filter' => $filter,
            'perSelect' => Idea::PER,
            'per' => $per,
            'page' => $page,
            'user' => $request->user()
        ]);
    }

    public function getTagsList(Request $request)
    {
        $tag = $request->t ?? '';

        if ($tag) {
            $tags = Tag::where('title', 'like', '%'.$tag.'%')
            ->limit(5)
            ->get();
        } else {
            $tags = [];
        }
        

        $html = view('front.tag-search-list')->with(['tags' => $tags])->render();
        
        return response()->json([
            'tags' => $html,
        ]);
    }

    public function addNewTag(Request $request)
    {
        $title = $request->tag ?? '';

        if (strlen($title) < 3) {
            return response()->json([
                'message' => 'Invalid tag title',
                'status' => 'error'
            ]);
        }
       
        return response()->json([
            'message' => 'Tag added',
            'status' => 'ok',
            'tag' => $title,
        ]);

    }

    public function deleteTag(Request $request, Idea $idea)
    {
        $tagId = $request->tag ?? 0;

        $tag = Tag::find($tagId);

        if (!$tag) {
            return response()->json([
                'message' => 'Invalid tag id',
                'status' => 'error'
            ]);
        }

        $ideaTag = IdeaTag::where('idea_id', $idea->id)
        ->where('tag_id', $tag->id)->first();

        $ideaTag->delete();
        return response()->json([
            'message' => 'Tag removed',
            'status' => 'ok',
            'tag' => $tag->title,
            'id' => $tag->id,
        ]);


    }

    public function addTag(Request $request)
    {
        $tagId = $request->tag ?? 0;

        $tag = Tag::find($tagId);

        if (!$tag) {
            return response()->json([
                'message' => 'Invalid tag id',
                'status' => 'error'
            ]);
        }

        return response()->json([
            'message' => 'Tag added',
            'status' => 'ok',
            'tag' => $tag->title,
        ]);
    }
    
    public function create(Request $request)
    {
        return view('front.create', [
            'user' => $request->user()
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3|max:100',
            'description' => 'required|min:3|max:2000',
            'funds' => 'required|numeric|decimal:0,2|gt:0',
            'photo' => 'sometimes|required|image|max:10240',
            'gallery.*' => 'sometimes|required|image|max:10240',
            'tags' => 'required|array',
        ]);

        if ($validator->fails()) {
            $request->flash();
            return redirect()
                ->back()
                ->withErrors($validator);
        }

        // main photo
        $photo = $request->photo;
        if ($photo) {
            $name = Photo::add($photo);
        }

        // tags
        $tags = [];
        foreach($request->tags as $tag)
        {
            $tagId = Tag::where('title', $tag)->value('id');

            if(!$tagId) {
                $tagId = Tag::create([
                    'title' => $tag
                ])->id;
            }

            $tags[] = $tagId;
        }

        $id = Idea::create([
            'title' => $request->title,
            'description' => $request->description,
            'funds' => $request->funds,
            'type' => 0,
            'created_at' => date("Y-m-d H:i:s"),
            'photo' => $name ?? null,
            'hearts' => [],
        ])->id;

        // gallery
        foreach ($request->gallery ?? [] as $gallery) {
            Photo::add($gallery, $id);
        }

        // tags pivot
        foreach($tags as $tagId)
        {
            IdeaTag::create([
                'tag_id' => $tagId,
                'idea_id' => $id
            ]);
        }
        //user
        $request->user()->update([
            'idea_id' => $id
        ]);
        
        return redirect()
        ->route('front-index')
        ->with('ok', 'New idea was created');
    }

    //back for sure
    public function confirm(Idea $idea)
    {
        $idea->type = 1;
        $idea->save();
        return redirect()
        ->route('admin-index')
        ->with('ok', 'Idea '.$idea->id.' was approved');
    }

    public function show(Request $request, Idea $idea)
    {
        $donations = $idea->donations->sortByDesc('created_at'); //sort

        // TAGS
        $tagsId = $idea->ideaTag->pluck('tag_id')->all();
        $tags = Tag::whereIn('id', $tagsId)->get();
        $idea->tags = $tags;

        return view('back.ideas.show', [
            'idea' => $idea,
            'user' => $request->user(),
            'donations' => $donations
        ]);
    }

    public function edit(Request $request)
    {
        if ($request->user()->idea_id == '0') {
            return redirect()
            ->route('front-create')
            ->with('info', 'Create idea first');
        }
        $idea = Idea::where('id', $request->user()->idea_id)->first();
        $tags = $idea->ideaTag()->pluck('tag_id');
        $tags = Tag::whereIn('id', $tags)->get();
        return view('front.edit', [
            'idea'=> $idea,
            'tags'=> $tags,
        ]);
    }

    public function update(Request $request, Idea $idea)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3|max:100',
            'description' => 'required|min:3|max:2000',
            'funds' => 'required|numeric|decimal:0,2|gt:0',
            'photo' => 'sometimes|required|image|max:10240',
            'gallery.*' => 'sometimes|required|image|max:10240',
            'tags' => 'required|array',
        ]);

        if ($validator->fails()) {
            $request->flash();
            return redirect()
                ->back()
                ->withErrors($validator);
        }

        if ($request->delete == 1) {
            $idea->deletePhoto();
            return redirect()->back();
        }

        $photo = $request->photo;

        if ($photo) {
            $name = Photo::add($photo);
            $idea->deletePhoto();
            $idea->update([
                'title' => $request->title,
                'description' => $request->description,
                'funds' => $request->funds,
                'photo' => $name
            ]);
        } else {
            $idea->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);
        }

        // old tags delete
        $oldTags = IdeaTag::where('idea_id', $idea->id)->get();
        foreach($oldTags as $oldTag) {
            $oldTag->delete();
        }
        // new tags
        $tags = [];
        foreach($request->tags as $tag)
        {
            $tagId = Tag::where('title', $tag)->value('id');

            if(!$tagId) {
                $tagId = Tag::create([
                    'title' => $tag
                ])->id;
            }

            $tags[] = $tagId;
        }
        // tags pivot
        foreach($tags as $tagId)
        {
            IdeaTag::create([
                'tag_id' => $tagId,
                'idea_id' => $idea->id
            ]);
        }

        //gallery
        foreach ($request->gallery ?? [] as $gallery) {
            Photo::add($gallery, $idea->id);
        }

        return redirect()
        ->route('front-index')
        ->with('ok', 'The idea was updated');
    }

    public function pledge(Request $request, Idea $idea)
    {
        if ($request->donator_id == '0') {
            return redirect()
            ->route('login');
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|decimal:0,2|gt:0',
            'donator_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            $request->flash();
            return redirect()
                ->back()
                ->withErrors($validator);
        }

        $pledge = new Donation;
        $pledge->amount= $request->amount;
        $pledge->idea_id = $idea->id;
        $pledge->donator_id = $request->donator_id;
        $pledge->created_at = date("Y-m-d H:i:s");
        $pledge->save();
        return redirect()
        ->route('front-index')
        ->with('ok', 'Your pledge was accepted');
    }

    public function like(Request $request, Idea $idea)
    {
        if ($request->heart_id == '0') {
            return redirect()
            ->route('login');
        }

        $validator = Validator::make($request->all(), [
            'heart_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator);
        }

        $hearts = $idea->hearts;
        $hearts[] = $request->heart_id;
        $idea->hearts = $hearts;
        $idea->save();
        return redirect()
        ->route('front-index')
        ->with('ok', 'Your like was saved');
    }

    public function gallery(Idea $idea)
    {
        return view('front.gallery', [
            'main' => $idea->photo,
            'gallery' => $idea->gallery()->get(),
        ]);
    }

    public function destroy(Idea $idea)
    {
        $idea->delete();
        return redirect()
        ->route('admin-index')
        ->with('info', 'The idea was deleted');
    }

}
