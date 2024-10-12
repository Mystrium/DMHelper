<?php

namespace App\Http\Controllers;

use App\Models\Game;


class PlayController extends Controller {
    function index($game_id) {
        $game = Game::findOrFail($game_id);
        $music_list = $game->music_list_id;
        
        return view('game.main', compact('game_id', 'music_list'));
    }

    function fight() {
        $players = [
            ['id' => 1, 'name' => 'vergil', 'initiative' => 2, 'hp' => 10, 'pos' => 0],
            ['id' => 2, 'name' => 'miric', 'initiative' => 3, 'hp' => 5, 'pos' => 0],
            ['id' => 3, 'name' => 'blacke', 'initiative' => 1, 'hp' => 6, 'pos' => 0],
        ];

        return view('game.fight', compact('players'));
    }

}
