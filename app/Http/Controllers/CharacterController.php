<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use App\Models\Character;

class CharacterController extends Controller {    
    public function index($gameId) {
        $apiurl = 'http://127.0.0.1:8001/api/character/';
        $is_owner = Game::find($gameId)->user_id == auth()->id();

        $ids = Character::where('game_id', $gameId)->get()->pluck('external_id');

        $characters = [];

        foreach($ids as $id)
            $characters[] = $this->fetchCharacterFromApi($apiurl . 'short/' . $id);

        return view('game.characters', compact('characters', 'gameId', 'apiurl', 'is_owner'));
    }

    public function create(Request $request, $gameId) {
        $name = $request->input('name');

        $characterData = $this->fetchCharacterFromApi($request->input('url'));

        if ($characterData && hash('sha256', $characterData['name']) === hash('sha256', $name)) {
            Character::create([
                'external_id' => $characterData['id'],
                'game_id' => $gameId,
                'name_hash' => hash('sha256', $name),
            ]);

            return back()->withErrors(['msg' => __('messages.added.character')]);
        } else
            return back()->withErrors(['msg' => __('messages.warning.character')]);
    }

    public function destroy($id) {
        $character = Character::where('external_id',$id)->first();

        if ($character) {
            $character->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    public function fetchCharacterFromApi($url){
        $client = new \GuzzleHttp\Client();
        $response = $client->get($url);

        if ($response->getStatusCode() == 200)
            return json_decode($response->getBody(), true);

        return null;
    }
}
