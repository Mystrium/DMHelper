<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/auth', 'auth');
Route::view('/map', 'map');
Route::view('/players', 'players');
Route::view('/story', 'story');
Route::view('/fight', 'fight');
Route::view('/music', 'music');