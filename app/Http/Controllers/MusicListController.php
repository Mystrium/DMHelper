<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MusicList;

class MusicListController extends Controller {
    function index() {
        $playlists = MusicList::where('visible', 1)
            ->with('music')
            ->with('user')
            ->orderBy('id', 'desc')
            ->paginate(6, ['*'], 'page', 1);

        $my = false;
        return view('playlists', compact('playlists', 'my'));
    }

    function mylist() {
        $playlists = MusicList::where('user_id', auth()->id())
            ->with('music')
            ->orderBy('id', 'desc')
            ->paginate(6, ['*'], 'page', 1);

        $my = true;
        return view('playlists', compact('playlists', 'my'));
    }

    function fetch(Request $request) {
        $page = $request->input('page', 1);
        $search = $request->input('query', '');

        $playlists = MusicList::when($request->has('my'), function ($q) {
                $q->where('user_id', auth()->id());
            })->when(!$request->has('my'), function ($q) {
                $q->where('visible', 1)
                    ->with('user');
            })->when($search != '', function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('title',   'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
                });
            })->with('music')
            ->orderBy('id', 'desc')
            ->paginate(6, ['*'], 'page', $page);
        
        return response()->json($playlists);
    }

    function create(Request $request) {
        $validated = $request->validate([
            'title' => 'required|max:30',
            'description' => 'max:100',
        ]);

        $playlist = MusicList::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'visible' => $request->has('visibility') ? 1 : 0
        ]);

        return redirect()->route('music', $playlist->id);
    }

    function update(Request $request, $id) {
        $playlist = MusicList::findOrFail($id);
        if ($playlist->user_id != auth()->id()) { abort(403); }

        $validated = $request->validate([
            'title' => 'required|max:30',
            'description' => 'max:100',
        ]);

        $playlist->title = $validated['title'];
        $playlist->description = $validated['description'];
        $playlist->visible = $request->has('visibility') ? 1 : 0;
        $playlist->save();

        return back()->withErrors(['msg' => 'Плейлист оновленно']);
    }

    function destroy($id) {
        $playlist = MusicList::findOrFail($id);
        if ($playlist->user_id != auth()->id()) { abort(403); }
        
        $playlist->delete();
        return back()->withErrors(['msg' => 'Плейлист видалено успішно']);
    }
}
