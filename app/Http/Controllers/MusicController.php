<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MusicCategory;
use App\Models\MusicList;
use App\Models\Music;

class MusicController extends Controller {
    public function index($musicListId) {
        $musicList = MusicList::findOrFail($musicListId);
        $musics = $musicList->music()->with('category')->get();
        $categories = MusicCategory::all();
        return view('game.music', compact('musics', 'musicList', 'categories'));
    }

    public function create(Request $request) {
        try{
            $validated = $request->validate([ 
                'music_category_id' => 'required|integer|exists:music_categories,id',
                'music_list_id' => 'required|integer|exists:music_lists,id',
                'youtube_url' => 'required|url'
            ]);

            $music = Music::create([
                'music_category_id' => $validated['music_category_id'],
                'music_list_id' => $validated['music_list_id'],
                'youtube_url' => explode('v=', $validated['youtube_url'])[1]
            ]);

            return response()->json(['music' => $music]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function destroy($id) {
        $music = Music::findOrFail($id);

        if ($music) {
            $music->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
}
