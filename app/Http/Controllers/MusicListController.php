<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MusicList;

class MusicListController extends Controller {
    function index() {
        $playlists = MusicList::with('music')->with('user')->orderBy('id', 'desc')->get();
        return view('social.playlists', compact('playlists'));
    }

    function mylist() {
        $playlists = MusicList::where('user_id', auth()->id())->with('music')->get();
        return view('playlists', compact('playlists'));
    }

    function create(Request $request) {
        $validated = $request->validate([
            'title' => 'required|max:30',
            'description' => 'max:100',
        ]);

        $playlist = new MusicList();
        $playlist->title = $validated['title'];
        $playlist->description = $validated['description'];
        $playlist->user_id = auth()->id();
        $playlist->save();

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
