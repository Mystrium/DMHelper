<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Character;

class CharacterController extends Controller {    
    public function index($gameId) {
        $apiurl = 'http://127.0.0.1:8001/api/character/';

        $ids = Character::where('game_id', $gameId)->get()->pluck('external_id');

        $characters = [];

        foreach($ids as $id)
            $characters[] = $this->fetchCharacterFromApi($apiurl . 'short/' . $id);

        return view('game.characters', compact('characters', 'gameId', 'apiurl'));
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

    public function update(Request $request){
//о, апдейт можна зробити простіше, взагалі без контролера, а через аякс, так як змінювати гравця на стороні АПІ треба без оновлення сторінки, на стороні апіхи просто переробити метод, щоб він приймав ід, та хеш для верифікації, і далі дані, які потрібно змінити
    }

    public function destroy($id) {
        $character = Character::findOrFail($id);

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
