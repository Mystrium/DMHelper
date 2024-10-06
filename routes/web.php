<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\MarkerController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\MusicListController;
use App\Http\Controllers\StoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () { return view('welcome'); });

Route::get( 'login',    [AuthController::class, 'index']);
Route::post('login',    [AuthController::class, 'login'])->name("login");
Route::post('register', [AuthController::class, 'register'])->name("register");

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::view('/players', 'game.players');
    Route::view('/story', 'game.story');
    Route::view('/fight', 'game.fight');

    Route::get(     'playlists',    [MusicListController::class, 'index'])->name('playlists');
    Route::post(    'playlist/add', [MusicListController::class, 'create'])->name('playlist.add');
    Route::put(     'playlist/{id}',[MusicListController::class, 'update'])->name('playlist.update');
    Route::delete(  'playlist/{id}',[MusicListController::class, 'destroy'])->name('playlist.destroy');

    Route::get(     'music/{id}',   [MusicController::class, 'index'])->name('music');
    Route::post(    'addmusic/{id}',[MusicController::class, 'store'])->name('music.add');
    Route::delete(  'music/{id}',   [MusicController::class, 'destroy'])->name('music.destroy');

    Route::get(     'game',         [GameController::class, 'index'])->name('game');
    Route::post(    'game/add',     [GameController::class, 'create'])->name('game.add');
    Route::put(     'game/{id}',    [GameController::class, 'update'])->name('game.update');
    Route::delete(  'game/{id}',    [GameController::class, 'destroy'])->name('game.destroy');

    Route::get(     'story/{id}',   [StoryController::class, 'index'])->name('story');
    Route::post(    'story/next',   [StoryController::class, 'next'])->name('story.next');
    Route::post(    'story/{id}',   [StoryController::class, 'create'])->name('story.add');
    Route::put(     'story/{id}',   [StoryController::class, 'update'])->name('story.update');
    Route::delete(  'story/{id}',   [StoryController::class, 'destroy'])->name('story.destroy');
    Route::get( 'story/play/{id}',  [StoryController::class, 'story'])->name('story.play');
    Route::get( 'story/test/{id}',  [StoryController::class, 'test'])->name('story.test');

    Route::get(     'map/{id}',     [MapController::class, 'index'])->name('map');
    Route::post(    'addmap/{id}',  [MapController::class, 'create'])->name('map.add');
    Route::post(     'updatemap',   [MapController::class, 'update'])->name('map.update');
    Route::delete(  'map/{id}',     [MapController::class, 'destroy'])->name('map.destroy');

    Route::post(   'addmarker',     [MarkerController::class, 'create'])->name('marker.add');
});