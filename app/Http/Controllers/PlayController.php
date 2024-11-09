<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Game;

class PlayController extends Controller {
    function index($game_id) {
        $game = Game::findOrFail($game_id);
        $music_list = $game->music_list_id;
        
        return view('game.main', compact('game_id', 'music_list'));
    }

    function fight($game_id) {
        $ids = Character::where('game_id', $game_id)->get()->pluck('external_id');
        $players = [];

        foreach($ids as $id)
            $players[$id] = $this->fetchCharacterFromApi( $id);

        return view('game.fight', compact('players'));
    }

    public function fetchCharacterFromApi($id){
        $client = new \GuzzleHttp\Client();
        $response = $client->get('http://127.0.0.1:8001/api/character/fight/' . $id);

        if ($response->getStatusCode() == 200)
            return json_decode($response->getBody(), true);

        return null;
    }

}
