@extends('layouts.layout')

@section('title', 'Гра')

@section('content')
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane show active" id="map" role="tabpanel" aria-labelledby="map-tab">
            <div id="map_cont"></div>
        </div>
        <div class="tab-pane" id="players" role="tabpanel" aria-labelledby="story-tab">
            <div id="players_cont">
                <h1 class="position-absolute top-50 start-50 translate-middle-x">in development...</h1>
            </div>
        </div>
        <div class="tab-pane" id="story" role="tabpanel" aria-labelledby="story-tab">
            <div id="story_cont"></div>
        </div>
        <div class="tab-pane" id="fight" role="tabpanel" aria-labelledby="story-tab">
            <div id="fight_cont">
                <h1 class="position-absolute top-50 start-50 translate-middle-x">in development...</h1>
            </div>
        </div>
        <div class="tab-pane" id="music" role="tabpanel" aria-labelledby="music-tab">
            <div id="music_cont"></div>
        </div>
    </div>

    <footer class="bg-dark text-white py-2 mt-auto footer fixed-bottom">
        <ul class="nav">
            <li class="nav-item px-2" role="presentation">
                <a class="nav-link btn btn-success text-dark active" data-bs-toggle="tab" href="#map" role="tab" aria-controls="map" aria-selected="true">Мапа</a>
            </li>            
            <li class="nav-item px-2" role="presentation">
                <a class="nav-link btn btn-success text-dark" data-bs-toggle="tab" href="#players" role="tab" aria-controls="players" aria-selected="false">Гравці</a>
            </li>
            <li class="nav-item px-2" role="presentation">
                <a class="nav-link btn btn-success text-dark" data-bs-toggle="tab" href="#story" role="tab" aria-controls="story" aria-selected="false">Сюжет</a>
            </li>
            <li class="nav-item px-2" role="presentation">
                <a class="nav-link btn btn-success text-dark" data-bs-toggle="tab" href="#fight" role="tab" aria-controls="fight" aria-selected="false">Бій</a>
            </li>
            <li class="nav-item px-2" role="presentation">
                <a class="nav-link btn btn-success text-dark" data-bs-toggle="tab" href="#music" role="tab" aria-controls="music" aria-selected="false">Музика</a>
            </li>
        </ul>
    </footer>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    loadPage('/map/3?play=true', 'map_cont');
    loadPage('/story/play/3?play=true', 'story_cont');
    loadPage('/music/11?play=true', 'music_cont');

    // loadPage('...', 'players_cont');
    // loadPage('...', 'fight_cont');
});

function loadPage(url, container) {
    $.ajax({
        url: url,
        success: function(response) {
            $('#' + container).html(response);
        },
        error: function() {
            $('#' + container).html('<p>Смерт сторінки...</p>');
        }
    });
}

window.addEventListener("beforeunload", function (e) {
    var confirmationMessage = "???";
    e.returnValue = confirmationMessage;
    return confirmationMessage;
});
</script>
@endsection

<!-- 
    todo fix map search dropdown
-->