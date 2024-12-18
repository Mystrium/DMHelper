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
            ->paginate(6, ['*'], 'page', 1);
        $my = false;
        return view('games', compact( 'games', 'my'));
    }

    function mygames(Request $request) {
        $playlists = [];
        $playlists['my']  = MusicList::where('user_id', auth()->id())->get();
        $playlists['all'] = MusicList::where('user_id', '<>', auth()->id())
            ->where('visible', 1)
            ->get();
        
        $games = Game::where('user_id', auth()->id())
            ->with('map')
            ->orderBy('id', 'desc')
            ->paginate(6, ['*'], 'page', 1);
        $my = true;
        return view('games', compact('playlists', 'games', 'my'));
    }

    function fetch(Request $request) {
        $page = $request->input('page', 1);
        $search = $request->input('query', '');

        $games = Game::when($request->has('my'), function ($q) {
                $q->where('user_id', auth()->id());
            })->when(!$request->has('my'), function ($q) {
                $q->where('visible', 1)
                    ->with('user');
            })->when($search != '', function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('title',   'like', '%' . $search . '%')
                        ->orWhere('setting', 'like', '%' . $search . '%');
                });
            })->with('map')
            ->orderBy('id', 'desc')
            ->paginate(6, ['*'], 'page', $page);
        
        return response()->json($games);
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

        return back()->withErrors(['msg' => __('messages.update.game')]);
    }

    function visible($id){
        $game = Game::findOrFail($id);
        $game->visible = !$game->visible;
        $game->save();
        return back()->withErrors(['msg' => __('messages.visible.game')]);
    }

    function destroy($id) {
        $game = Game::findOrFail($id);
        if ($game->user_id != auth()->id()) { abort(403); }
        
        $game->delete();
        return back()->withErrors(['msg' => __('messages.deleted.game')]);
    }
}
