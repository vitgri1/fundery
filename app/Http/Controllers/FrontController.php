<?php

namespace App\Http\Controllers;

use App\Models\Idea;
// use App\Models\Donation;
use App\Models\Tag;
// use App\Models\Photo;
// use App\Models\IdeaTag;
use Illuminate\Http\Request;
// use Intervention\Image\ImageManagerStatic as Image;

class FrontController extends Controller
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

        return view('front.ideas.index', [
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
}
