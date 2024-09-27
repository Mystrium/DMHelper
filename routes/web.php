<?php

use App\Http\Controllers\MusicController;
use App\Http\Controllers\MusicListController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () { return view('welcome'); });

Route::view('/auth', 'auth');

Route::post('login',    [AuthController::class, 'login'])->name("login");

Route::post('register', [AuthController::class, 'register'])->name("register");

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::view('/game', 'games')->name('game');

    Route::view('/map', 'game.map');
    Route::view('/players', 'game.players');
    Route::view('/story', 'game.story');
    Route::view('/fight', 'game.fight');

    Route::get('/playlists',        [MusicListController::class, 'index'])->name('playlists');
    Route::post('addplaylist',      [MusicListController::class, 'create'])->name('addplaylist');
    Route::put('/playlist/{id}',   [MusicListController::class, 'update'])->name('playlist.update');
    Route::delete('playlist/{id}',  [MusicListController::class, 'destroy'])->name('playlist.destroy');

    Route::get('/music/{id}',       [MusicController::class, 'index'])->name('music');
    Route::post('addmusic/{id}',    [MusicController::class, 'store'])->name('addmusic');
    Route::delete('music/{id}',     [MusicController::class, 'destroy'])->name('music.destroy');

});