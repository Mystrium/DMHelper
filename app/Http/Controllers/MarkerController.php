<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MapMarker;
use App\Models\Map;

class MarkerController extends Controller {
    function index($game_id) {
        $maps = Map::where('game_id', $game_id)->get();
        return view('game.map', compact('maps', 'game_id'));
    }

    function create(Request $request) {
        $request->validate([
            'map_id' => 'required|integer|exists:maps,id',
            'title' => 'required|string|max:40',
            'text' => 'nullable|string|max:300',
            'x' => 'required|integer',
            'y' => 'required|integer',
        ]);
    
        $marker = MapMarker::create([
            'map_id' => $request->map_id,
            'title' => $request->title,
            'text' => $request->text,
            'x' => $request->x,
            'y' => $request->y
        ]);
    
        return response()->json(['msg' => 'Map update successfully', 'marker' => $marker]);
    }

    function update(Request $request) {
        $map = Map::findOrFail($request->map_id);

        $validated = $request->validate(rules: ['title' => 'required|max:30']);
        $map->title = $validated['title'];
        $map->save();

        return response()->json(['msg' => 'Map update successfully', 'map' => $map]);
    }


    function destroy($id) {
        $map = MapMarker::findOrFail($id);
        $map->delete();
        return back()->withErrors(['msg' => 'Мапу видалено успішно']);
    }
}
