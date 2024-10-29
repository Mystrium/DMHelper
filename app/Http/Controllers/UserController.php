<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MusicList;
use App\Models\Game;
use App\Models\User;

class UserController extends Controller {
    function index() {
        $users = User::orderBy('id', 'desc')->get();
        return view('social.users', compact('users'));
    }

    function profile($id) {
        $user = User::findOrFail($id);
        $games = Game::where('user_id', $user->id)->with('map')->get();
        $playlists = MusicList::where('user_id', $user->id)->with('music')->get();

        return view('social.profile', compact('user', 'games', 'playlists'));
    }

    function update(Request $request, $id) {
        $user = User::findOrFail($id);
        if ($user->id != auth()->id()) { abort(403); }

        $validname = $request->validate([
            'name' => 'required|string|max:30',
        ]);
        $user->name = $validname['name'];

        if($user->email != $request->input('email')){
            $validemail = $request->validate([
                'email' => 'required|email|max:50|unique:users',
            ]);
            $user->email = $validemail['email'];
        }

        $user->save();

        return back()->withErrors(['msg' => 'Профіль оновленно']);
    }

    // function destroy($id) {
    //     $game = Game::findOrFail($id);
    //     if ($game->user_id != auth()->id()) { abort(403); }
        
    //     $game->delete();
    //     return back()->withErrors(['msg' => 'Гру видалено успішно']);
    // }
}
