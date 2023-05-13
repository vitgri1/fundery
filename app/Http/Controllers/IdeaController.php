<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use App\Models\Donation;
use App\Models\Tag;
use App\Models\Photo;
use App\Models\IdeaTag;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

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

    public function addNewTag(Request $request, Idea $idea)
    {
        $title = $request->tag ?? '';

        if (strlen($title) < 3) {
            return response()->json([
                'message' => 'Invalid tag title',
                'status' => 'error'
            ]);
        }

        $tag = Tag::where('title', $title)->first();

        if (!$tag) {

            $tag = Tag::create([
                'title' => $title
            ]);
        }

        $tagsId = $idea->ideaTag->pluck('tag_id')->all();
        
        if (in_array($tag->id, $tagsId)) {
            return response()->json([
                'message' => 'Tag exists',
                'status' => 'error'
            ]);
        }

        IdeaTag::create([
            'tag_id' => $tag->id,
            'idea_id' => $idea->id
        ]);

        return response()->json([
            'message' => 'Tag added',
            'status' => 'ok',
            'tag' => $tag->title,
            'id' => $tag->id,
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

    public function addTag(Request $request, Idea $idea)
    {
        $tagId = $request->tag ?? 0;

        $tag = Tag::find($tagId);

        if (!$tag) {
            return response()->json([
                'message' => 'Invalid tag id',
                'status' => 'error'
            ]);
        }

        $tagsId = $idea->ideaTag->pluck('tag_id')->all();
        
        if (in_array($tagId, $tagsId)) {
            return response()->json([
                'message' => 'Tag exists',
                'status' => 'error'
            ]);
        }

        IdeaTag::create([
            'tag_id' => $tagId,
            'idea_id' => $idea->id
        ]);


        return response()->json([
            'message' => 'Tag added',
            'status' => 'ok',
            'tag' => $tag->title,
            'id' => $tag->id,
        ]);
    }
    
    public function create()
    {
        return view('back.ideas.create', [
            'tags' => Tag::all()
        ]);
    }

    public function store(Request $request)
    {
        $photo = $request->photo;
        if ($photo) {
            $name = Idea::savePhoto($photo);
        }

        $id = Idea::create([
            'title' => $request->title,
            'description' => $request->description,
            'funds' => $request->funds,
            'type' => 0,
            'created_at' => date("Y-m-d H:i:s"),
            'photo' => $name ?? null,
            'tag_ids' => [$request->tags],
            'hearts' => [],
        ])->id;

        foreach ($request->gallery ?? [] as $gallery) {
            Photo::add($gallery, $id);
        }
        
        return redirect()
        ->route('ideas-index')
        ->with('ok', 'New idea was created');
    }

    public function show(Idea $idea)
    {
        $donations = $idea->donations->sortByDesc('created_at'); //sort
        return view('back.ideas.show', [
            'idea' => $idea,
            'donations' => $donations
        ]);
    }

    public function confirm(Idea $idea)
    {
        $idea->type = 1;
        $idea->save();
        return redirect()
        ->route('ideas-index')
        ->with('ok', 'Idea '.$idea->id.' was confirmed');
    }

    public function edit(Idea $idea)
    {
        return view('back.ideas.edit', [
            'idea' => $idea,
            'tags' => Tag::all()
        ]);
    }

    public function update(Request $request, Idea $idea)
    {

        if ($request->delete == 1) {
            $idea->deletePhoto();
            return redirect()->back();
        }

        $photo = $request->photo;

        if ($photo) {
            $name = Idea::savePhoto($photo);
            $idea->deletePhoto();
            $idea->update([
                'title' => $request->title,
                'description' => $request->description,
                'photo' => $name
            ]);
        } else {
            $idea->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);
        }

        foreach ($request->gallery ?? [] as $gallery) {
            Photo::add($gallery, $idea->id);
        }

        return redirect()
        ->route('ideas-index')
        ->with('ok', 'The idea was updated');
    }

    public function pledge(Request $request, Idea $idea)
    {
        $pledge = new Donation;
        $pledge->amount= $request->amount;
        $pledge->idea_id = $idea->id;
        $pledge->donator_id = $request->donator_id;
        $pledge->created_at = date("Y-m-d H:i:s");
        $pledge->save();
        return redirect()
        ->route('ideas-index')
        ->with('ok', 'Your pledge was accepted');
    }

    public function like(Request $request, Idea $idea)
    {
        $hearts = $idea->hearts;
        $hearts[] = $request->heart_id;
        $idea->hearts = $hearts;
        $idea->save();
        return redirect()
        ->route('ideas-index')
        ->with('ok', 'Your like was saved');
    }

    public function destroy(Idea $idea)
    {
        $idea->delete();
        return redirect()
        ->route('ideas-index')
        ->with('info', 'The idea was deleted');
    }

    public function destroyPhoto(Photo $photo)
    {
        $photo->deletePhoto();
        return redirect()->back();
    }
}
