<?php

use App\Http\Controllers\CharacterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MusicListController;
use App\Http\Controllers\MarkerController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlayController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\MapController;

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'uk'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

Route::get('/', function () { return view('welcome'); });

Route::get( 'login',    [AuthController::class, 'index']);
Route::post('login',    [AuthController::class, 'login'])->name("login");
Route::post('register', [AuthController::class, 'register'])->name("register");

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get(     'playlists',    [MusicListController::class, 'index'])->name('playlists');
    Route::get(     'playlists/my', [MusicListController::class, 'mylist'])->name('playlists.my');
    Route::get(  'playlists/fetch', [MusicListController::class, 'fetch'])->name('playlists.ajax');
    Route::post(    'playlist/add', [MusicListController::class, 'create'])->name('playlist.add');
    Route::put('playlist/visible/{id}', [MusicListController::class, 'visible'])->name('playlist.visible');
    Route::put(     'playlist/{id}',[MusicListController::class, 'update'])->name('playlist.update');
    Route::delete(  'playlist/{id}',[MusicListController::class, 'destroy'])->name('playlist.destroy');

    Route::get(     'music/{id}',   [MusicController::class, 'index'])->name('music');
    Route::post(    'addmusic',     [MusicController::class, 'create'])->name('music.add');
    Route::delete(  'music/{id}',   [MusicController::class, 'destroy'])->name('music.destroy');

    Route::get(     'games',        [GameController::class, 'index'])->name('game');
    Route::get(     'games/my',     [GameController::class, 'mygames'])->name('game.my');
    Route::get(     'games/fetch',  [GameController::class, 'fetch'])->name('game.ajax');
    Route::post(    'game/add',     [GameController::class, 'create'])->name('game.add');
    Route::put('game/visible/{id}', [GameController::class, 'visible'])->name('game.visible');
    Route::put(     'game/{id}',    [GameController::class, 'update'])->name('game.update');
    Route::delete(  'game/{id}',    [GameController::class, 'destroy'])->name('game.destroy');

    Route::get(     'map/{id}',     [MapController::class, 'index'])->name('map');
    Route::post(    'addmap/{id}',  [MapController::class, 'create'])->name('map.add');
    Route::post(    'updatemap',    [MapController::class, 'update'])->name('map.update');
    Route::delete(  'map/{id}',     [MapController::class, 'destroy'])->name('map.destroy');

    Route::post(   'addmarker',     [MarkerController::class, 'create'])->name('marker.add');
    Route::delete('marker/{id}',    [MarkerController::class, 'destroy'])->name('marker.delete');
    Route::post(  'updatemarker',   [MarkerController::class, 'update'])->name('marker.update');

    Route::get(     'story/{id}',   [StoryController::class, 'index'])->name('story');
    Route::post(    'addstory',     [StoryController::class, 'create']);
    Route::post(    'updatestory',  [StoryController::class, 'update']);
    Route::post(    'pausestory',   [StoryController::class, 'pause']);
    Route::delete(  'delstory/{id}',[StoryController::class, 'destroy']);
    Route::post(    'story/next',   [StoryController::class, 'next'])->name('story.next');

    Route::get(    'play/{game_id}',[PlayController::class, 'index'])->name('play');
    Route::get(   'fight/{game_id}',[PlayController::class, 'fight'])->name('fight');

    Route::get(    'users/',        [UserController::class, 'index'])->name('profile.all');
    Route::post(   'users/ban',     [UserController::class, 'ban']);
    Route::get(    'profile/{id}',  [UserController::class, 'profile'])->name('profile');
    Route::put(    'profile/{id}',  [UserController::class, 'update'])->name('profile.update');

    Route::get(   'characters/{id}',[CharacterController::class, 'index'])->name('characters');
    Route::post(   'character/{id}',[CharacterController::class, 'create'])->name('character.add');
    Route::delete( 'character/{id}',[CharacterController::class, 'destroy'])->name('character.destroy');
});