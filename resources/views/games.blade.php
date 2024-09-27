@extends('layouts.layout')

@section('title', 'Сесії')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-4 game-card-container">
            <a href="/map" style="text-decoration: none">
                <div class="game-card" style="background-image: url('https://via.placeholder.com/500x300');">
                    <div class="game-card-overlay">
                        <div class="game-title">Назва гри 1</div>
                        <div class="game-description">Опис першої гри користувача. Досліджуйте світ та перемагайте ворогів.</div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 game-card-container">
            <a href="/map" style="text-decoration: none">
                <div class="game-card" style="background-image: url('https://via.placeholder.com/500x300');">
                    <div class="game-card-overlay">
                        <div class="game-title">Назва гри 2</div>
                        <div class="game-description">Опис другої гри. Відчуйте неймовірну пригоду на нових територіях.</div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 game-card-container">
            <a href="/map" style="text-decoration: none">
                <div class="game-card" style="background-image: url('https://via.placeholder.com/500x300');">
                    <div class="game-card-overlay">
                        <div class="game-title">Назва гри 2</div>
                        <div class="game-description">Опис другої гри. Відчуйте неймовірну пригоду на нових територіях.</div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 game-card-container">
            <a href="/map" style="text-decoration: none">
                <div class="game-card" style="background-image: url('https://via.placeholder.com/500x300');">
                    <div class="game-card-overlay">
                        <div class="game-title">Назва гри 3</div>
                        <div class="game-description">Опис третьої гри. Об'єднайте сили з друзями і вигравайте в захоплюючі битви.</div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 game-card-container">
            <a href="/map" style="text-decoration: none">
                <div class="game-card" style="background-color: lightgreen;">
                    <div class="game-card-overlay">
                        <div class="game-title">Нова історія</div>
                        <div class="game-description">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>
        </div>

    </div>
</div>
@endsection