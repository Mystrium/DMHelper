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
        try {
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
            return response()->json(['marker' => $marker]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        }
    }

    function update(Request $request) {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:40',
                'text' => 'nullable|string|max:300'
            ]);
    
            $marker = MapMarker::findOrFail($request->id);
    
            $marker->title = $validated['title'];
            $marker->text  = $validated['text'];
            $marker->save();
    
            return response()->json(['success' => true]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        }
    }


    public function destroy($id){
        $marker = MapMarker::findOrFail($id);
        
        if ($marker) {
            $marker->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

}
