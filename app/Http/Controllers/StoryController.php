<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StoryLink;
use App\Models\Story;
use App\Models\Game;

class StoryController extends Controller {
    public function index($gameId) {
        $game = Game::findOrFail($gameId);
        $blocks = $game->story()->orderBy('id', 'desc')->get();
        return view('game.story', compact( 'game', 'blocks'));
    }

    public function create(Request $request, $gameId) {
        $request->validate([ 
            'title' => 'required|string|max:50',
            'text' => 'required|string|max:300'
        ]);

        Story::create([
            'title' => $request->input('title'),
            'text' => $request->input('text'),
            'game_id' => $gameId
        ]);

        return back()->withErrors(['msg' => 'Блок доданий']);
    }

    public function destroy($id) {
        $story = Story::findOrFail($id);

        $story->delete();
        return back()->withErrors(['msg' => 'Блок видалена']);
    }
}
