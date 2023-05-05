<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use App\Models\Donation;
use Illuminate\Http\Request;

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

    public function create()
    {
        return view('back.ideas.create');
    }

    public function store(Request $request)
    {
        $idea = new Idea;
        $idea->title = $request->title;
        $idea->description = $request->description;
        $idea->funds = $request->funds;
        $idea->type = 0;
        $idea->save();
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
            'idea' => $idea
        ]);
    }

    public function update(Request $request, Idea $idea)
    {
        $idea->title = $request->title;
        $idea->description = $request->description;
        $idea->save();
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

    public function destroy(Idea $idea)
    {
        $idea->delete();
        return redirect()
        ->route('ideas-index')
        ->with('info', 'The idea was deleted');
    }
}
