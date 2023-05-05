<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->sort ?? '';
        $filter = $request->filter ?? '';
        $per = (int) ($request->per ?? 10);
        $page = $request->page ?? 1;

        $tags = match($filter) {
            default => Tag::where('id', '>', 0),
        };

        $tags = match($sort) {
            'title_asc' => $tags->orderBy('title'),
            'title_desc' => $tags->orderBy('title', 'desc'),
            default => $tags->orderBy('title')
        };

        $tags = $tags->paginate($per)->withQueryString();

        return view('back.tags.index', [
            'tags' => $tags,
            'sortSelect' => Tag::SORT,
            'sort' => $sort,
            'filterSelect' => Tag::FILTER,
            'filter' => $filter,
            'perSelect' => Tag::PER,
            'per' => $per,
            'page' => $page
        ]);
    }

    public function create()
    {
        return view('back.tags.create');
    }

    public function store(Request $request)
    {
        $tag = new Tag;
        $tag->title = $request->title;
        $tag->save();
        return redirect()
        ->route('tags-index')
        ->with('ok', 'New tag was created');
    }

    public function show(Tag $tag)
    {
        return view('back.tags.show', [
            'tag' => $tag
        ]);
    }

    public function edit(Tag $tag)
    {
        return view('back.tags.edit', [
            'tag' => $tag
        ]);
    }

    public function update(Request $request, Tag $tag)
    {
        $tag->title = $request->title;
        $tag->save();
        return redirect()
        ->route('tags-index')
        ->with('ok', 'The tag was updated');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()
        ->route('tags-index')
        ->with('info', 'The tag was deleted');
    }
}
