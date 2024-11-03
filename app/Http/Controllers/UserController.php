<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MusicList;
use App\Models\Game;
use App\Models\User;

class UserController extends Controller {
    function index() {
        $users = User::orderBy('banned', 'asc')
            ->orderBy('id', 'desc')
            ->get();
        return view('social.users', compact('users'));
    }

    function profile($id) {
        $user = User::findOrFail($id);
        $games = Game::where('visible', 1)
            ->where('user_id', $user->id)
            ->with('map')
            ->get();
        $playlists = MusicList::where('visible', 1)
            ->where('user_id', $user->id)
            ->with('music')
            ->get();

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

        return back()->withErrors(['msg' => __('messages.updated.profile')]);
    }

    function ban(Request $request) {
        $user = User::findOrFail($request->id);
        if (!auth()->user()->is_admin) { abort(403); }
        
        $user->banned = !$user->banned;
        $user->save();

        $msg = $user->banned == 1 ? __('messages.user.ban') : __('messages.user.unban');
        return back()->withErrors(['msg' => $msg]);
    }
}
