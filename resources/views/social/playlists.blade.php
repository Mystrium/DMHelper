@extends('layouts.layout')

@section('title', 'Плейлисти')

@section('content')
<div class="container my-5">
    <div class="row">
        @foreach($playlists as $playlist)
            <div class="col-md-4 mb-4">
                <a href="/music/{{$playlist->id}}" style="text-decoration: none">
                    <div class="game-card" style="background-image: url('https://img.youtube.com/vi/{{isset($playlist->music[0]) ? $playlist->music[mt_rand(0,count($playlist->music) - 1)]->youtube_url : ''}}/0.jpg');">
                        <div class="game-card-overlay">
                            <div><h3 class="text-white">{{$playlist->title}}</h3></div>
                            <div><h7 class="text-white">{{$playlist->description}}</h7></div>
                        </div>

                        <div class="flex position-absolute bottom-0 end-0 mb-2 me-2 bg-dark">
                            <a href="/profile/{{ $playlist->user_id }}" class="list-group-item">{{ $playlist->user->name }}</a>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection