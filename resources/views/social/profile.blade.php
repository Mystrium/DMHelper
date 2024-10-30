@extends('layouts.layout')

@section('title', 'Профіль')

@section('content')
@php $is_owner = Auth::user()->id == $user->id; @endphp
<div class="container my-5">
    <div class="user-info mb-5 text-center">
        <h2>Профіль: {{ $user->name }}</h2>
        <p>Email: {{ $user->email }}</p>
        @if($is_owner)
            <button data-bs-toggle="modal" data-bs-target="#changeUser" type="submit" class="btn btn-warning btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                    <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001m-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708z"/>
                </svg>
            </button>
        @endif
    </div>

    <ul class="nav nav-tabs justify-content-center" id="profileTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="games-tab" data-bs-toggle="tab" data-bs-target="#games" type="button" role="tab" aria-controls="games" aria-selected="true">Ігри</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="playlists-tab" data-bs-toggle="tab" data-bs-target="#playlists" type="button" role="tab" aria-controls="playlists" aria-selected="false">Плейлисти</button>
        </li>
    </ul>

    <div class="tab-content mt-4" id="profileTabsContent">
        <div class="tab-pane fade show active" id="games" role="tabpanel" aria-labelledby="games-tab">
            <div class="row">
                @foreach($games as $game)
                    <div class="col-md-4 mb-4">
                        <a href="/{{$is_owner ? 'story' : 'play'}}/{{ $game->id }}" style="text-decoration: none;">
                            <div class="game-card" style="background-image: url(/storage/maps/{{ isset($game->map[0]) ? $game->map[mt_rand(0, count($game->map) - 1)]->file_name : '' }});">
                                <div class="game-card-overlay">
                                    <h3 class="text-white">{{ $game->title }}</h3>
                                    <h7 class="text-white">{{ $game->setting }}</h7>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="tab-pane fade" id="playlists" role="tabpanel" aria-labelledby="playlists-tab">
            <div class="row">
                @foreach($playlists as $playlist)
                    <div class="col-md-4 md-4 position-relative">
                        <a href="/music/{{$playlist->id}}" style="text-decoration: none">
                            <div class="game-card" style="background-image: url('https://img.youtube.com/vi/{{isset($playlist->music[0]) ? $playlist->music[mt_rand(0,count($playlist->music) - 1)]->youtube_url : ''}}/0.jpg');">
                                <div class="game-card-overlay">
                                    <div><h3>{{$playlist->title}}</h3></div>
                                    <div><h7>{{$playlist->description}}</h7></div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@if($is_owner)
    <div class="modal fade" id="changeUser" tabindex="-1" aria-labelledby="changeUserLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changeUserLabel">Змінити дані</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editform" method="POST" action="/profile/{{ $user->id }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="gameTitle" class="form-label">Ім'я</label>
                            <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="gameSetting" class="form-label">Пошта (логін)</label>
                            <textarea class="form-control" name="email">{{ $user->email }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-warning">Змінити</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection