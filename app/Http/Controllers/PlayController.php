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
            1 => ['name' => 'rain',     'initiative' => 3, 'hp' => 10, 'armor' => 14],
            2 => ['name' => 'slimak',   'initiative' => 2, 'hp' => 7,  'armor' => 10],
            4 => ['name' => 'buter',    'initiative' => 2, 'hp' => 9,  'armor' => 14],
            5 => ['name' => 'astros',   'initiative' => 1, 'hp' => 7,  'armor' => 10],
        ];

        return view('game.fight', compact('players'));
    }

}
