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

    public function store(Request $request, $musicListId) {
        $request->validate([ 'youtube_url' => 'required|url' ]);
        Music::create([
            'music_category_id' => $request->input('music_category_id'),
            'music_list_id' => $musicListId,
            'youtube_url' => explode('v=', $request->input('youtube_url'))[1]
        ]);

        return redirect()->route('music', $musicListId)->with('success', 'Пісня додана.');
    }

    public function destroy($id) {
        $music = Music::findOrFail($id);
        $musicListId = $music->music_list_id;

        $music->delete();
        return redirect()->route('music', $musicListId)->with('success', 'Пісня видалена.');
    }
}
