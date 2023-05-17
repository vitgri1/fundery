<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use App\Models\Tag;
use App\Models\IdeaTag;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->sort ?? '';
        $filter = $request->filter ?? '';
        $per = (int) ($request->per ?? 10);
        $page = $request->page ?? 1;

        $ideas = Idea::where('type', 1);

        $ideaTags = IdeaTag::where('tag_id', $filter)->pluck('idea_id')->all();

        if ($filter == 0 || $filter ===''){
            $ideas->where('id', '>', 0);
        } else {
            $ideas = $ideas->whereIn('id', $ideaTags);
        }

        // $ideas = match($filter) {
        //     default => $ideas->where('id', '>', 0),
        // };

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

        return view('front.index', [
            'ideas' => $ideas,
            'sortSelect' => Idea::SORT,
            'sort' => $sort,
            'filterSelect' => Tag::all(),
            'filter' => $filter,
            'perSelect' => Idea::PER,
            'per' => $per,
            'page' => $page,
            'user' => $request->user(),
        ]);
    }

    public function show(Request $request, Idea $idea)
    {
        $donations = $idea->donations->sortByDesc('created_at'); //sort

        // TAGS
        $tagsId = $idea->ideaTag->pluck('tag_id')->all();
        $tags = Tag::whereIn('id', $tagsId)->get();
        $idea->tags = $tags;

        return view('front.show', [
            'idea' => $idea,
            'user' => $request->user(),
            'donations' => $donations
        ]);
    }
}
