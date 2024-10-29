@extends('layouts.layout')

@section('title', 'Ігри')

@section('content')
<div class="container my-5">
    <div class="row">
        @foreach($games as $game)
            <div class="col-md-4 mb-4">
                <a href="/play/{{$game->id}}" style="text-decoration: none">
                    <div class="game-card" style="background-image: url(/storage/maps/{{isset($game->map[0]) ? $game->map[mt_rand(0,count($game->map) - 1)]->file_name : ''}});">
                        <div class="game-card-overlay">
                            <div><h3 class="text-white">{{$game->title}}</h3></div>
                            <div><h7 class="text-white">{{$game->setting}}</h7></div>
                        </div>

                        <div class="flex position-absolute bottom-0 end-0 mb-2 me-2 bg-dark">
                            <a href="/profile/{{ $game->user_id }}" class="list-group-item">{{ $game->user->name }}</a>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>

@endsection