<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use App\Models\Donation;
use App\Models\Tag;
// use App\Models\Photo;
// use App\Models\IdeaTag;
use Illuminate\Http\Request;
// use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Validator;

class FrontController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->sort ?? '';
        $filter = $request->filter ?? '';
        $per = (int) ($request->per ?? 10);
        $page = $request->page ?? 1;

        $ideas = Idea::where('type', 1);

        $ideas = match($filter) {
            default => $ideas->where('id', '>', 0),
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

        return view('front.index', [
            'ideas' => $ideas,
            'sortSelect' => Idea::SORT,
            'sort' => $sort,
            'filterSelect' => Idea::FILTER,
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
        return view('front.show', [
            'idea' => $idea,
            'user' => $request->user(),
            'donations' => $donations
        ]);
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

    public function pledge(Request $request, Idea $idea)
    {
        if ($request->donator_id == '0') {
            return redirect()
            ->route('login');
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|decimal:0,2|gte:0',
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
}
