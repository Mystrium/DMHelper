<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Map;

class MapController extends Controller {
    function index($game_id) {
        $maps = Map::where('game_id', $game_id)->with('markers')->get();
        return view('game.map', compact('maps', 'game_id'));
    }

    function create(Request $request, $game_id) {
        $request->validate([
            'title' => 'required||max:30',
            'map_file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $file = $request->file('map_file');
        $fileName = time();
        // $fileName = time().'_'.$file->getClientOriginalName();

        $file->storeAs('maps', $fileName, 'public');

        $map = new Map();
        $map->game_id = $game_id;
        $map->title = $request->title;
        $map->file_name = $fileName;
        $map->save();

        return response()->json(['msg' => 'Map created successfully', 'map' => $map]);
    }

    function update(Request $request) {
        $map = Map::findOrFail($request->map_id);

        $validated = $request->validate(rules: ['title' => 'required|max:30']);
        $map->title = $validated['title'];
        $map->save();

        return response()->json(['msg' => 'Map update successfully', 'map' => $map]);
    }


    function destroy($id) {
        $map = Map::findOrFail($id);
        $map->delete();
        return back()->withErrors(['msg' => 'Мапу видалено успішно']);
    }
}
