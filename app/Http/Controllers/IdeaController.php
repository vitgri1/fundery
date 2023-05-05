<?php

namespace App\Http\Controllers;

use App\Models\Idea;
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
            'page' => $page
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
        $idea->type = 0;
        $idea->save();
        return redirect()
        ->route('ideas-index')
        ->with('ok', 'New idea was created');
    }

    public function show(Idea $idea)
    {
        return view('back.ideas.show', [
            'idea' => $idea
        ]);
    }

    public function confirm(Idea $idea)
    {
        dump($idea);
        $idea->type = 1;
        // $idea->save();
        // return redirect()
        // ->route('ideas-index')
        // ->with('ok', 'Idea '.$idea->id.' was confirmed');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Idea $idea)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Idea $idea)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Idea $idea)
    {
        //
    }
}
