<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MusicList;
use App\Models\Game;

class GameController extends Controller {
    function index() {
        $games = Game::where('visible', 1)
            ->with('map')
            ->with('user')
            ->orderBy('id', 'desc')
            ->get();
        return view('social.games', compact( 'games'));
    }

    function mygames() {
        $playlists = MusicList::where('user_id', auth()->id())->get();
        $games = Game::where('user_id', auth()->id())
            ->with('map')
            ->orderBy('id', 'desc')
            ->get();
        return view('games', compact('playlists', 'games'));
    }

    function create(Request $request) {
        $request->validate([
            'title' => 'required|string|max:50',
            'setting' => 'nullable|string|max:200',
            'music_list_id' => 'nullable|exists:music_lists,id'
        ]);
    
        $newgame = Game::create([
            'user_id' => auth()->id(),
            'title' => $request->input('title'),
            'setting' => $request->input('setting'),
            'music_list_id' => $request->input('music_list_id'),
            'visible' => $request->has('visibility') ? 1 : 0
        ]);

        return redirect()->route('story', $newgame->id);
    }

    function update(Request $request, $id) {
        $game = Game::findOrFail($id);
        if ($game->user_id != auth()->id()) { abort(403); }

        $validated = $request->validate([
            'title' => 'required|string|max:50',
            'setting' => 'nullable|string|max:200',
            'music_list_id' => 'nullable|exists:music_lists,id'
        ]);

        $game->title = $validated['title'];
        $game->setting = $validated['setting'];
        $game->music_list_id = $validated['music_list_id'];
        $game->visible = $request->has('visibility') ? 1 : 0;
        $game->save();

        return back()->withErrors(['msg' => 'Гру оновленно']);
    }

    function destroy($id) {
        $game = Game::findOrFail($id);
        if ($game->user_id != auth()->id()) { abort(403); }
        
        $game->delete();
        return back()->withErrors(['msg' => 'Гру видалено успішно']);
    }
}
