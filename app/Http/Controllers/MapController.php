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

        return back()->withErrors(['msg' => __('messages.added.map')]);
    }

    function update(Request $request) {
        try {
            $map = Map::findOrFail($request->map_id);

            $validated = $request->validate( ['title' => 'required|max:30']);
            $map->title = $validated['title'];
            $map->save();

            return response()->json(['success' => true]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        }
    }


    function destroy($id) {
        $map = Map::findOrFail($id);
        $map->delete();
        return back()->withErrors(['msg' => __('messages.deleted.map')]);
    }
}
