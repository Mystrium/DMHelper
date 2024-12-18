@extends('layouts.layout')

@section('title', __('headers.game'))

@section('content')
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane" id="map" role="tabpanel" aria-labelledby="map-tab">
            <div id="map_cont"></div>
        </div>
        <div class="tab-pane" id="players" role="tabpanel" aria-labelledby="story-tab">
            <div id="chars_cont">
            </div>
        </div>
        <div class="tab-pane show active" id="story" role="tabpanel" aria-labelledby="story-tab">
            <div id="story_cont"></div>
        </div>
        <div class="tab-pane" id="fight" role="tabpanel" aria-labelledby="story-tab">
            <div id="fight_cont"></div>
        </div>
        <div class="tab-pane" id="music" role="tabpanel" aria-labelledby="music-tab">
            <div id="music_cont"></div>
        </div>
    </div>

    <div id="pageLoader" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); z-index: 9999; display: flex; align-items: center; justify-content: center;">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <footer class="bg-dark text-white py-2 mt-auto footer fixed-bottom">
        <ul class="nav">
            <li class="nav-item px-2" role="presentation">
                <a class="nav-link btn btn-success text-dark" data-bs-toggle="tab" href="#map" role="tab" aria-controls="map" aria-selected="true">{{__('links.map')}}</a>
            </li>            
            <li class="nav-item px-2" role="presentation">
                <a class="nav-link btn btn-success text-dark" data-bs-toggle="tab" href="#players" role="tab" aria-controls="players" aria-selected="false">{{__('links.players')}}</a>
            </li>
            <li class="nav-item px-2" role="presentation">
                <a class="nav-link btn btn-success text-dark active" data-bs-toggle="tab" href="#story" role="tab" aria-controls="story" aria-selected="false">{{__('links.story')}}</a>
            </li>
            <li class="nav-item px-2" role="presentation">
                <a class="nav-link btn btn-success text-dark" data-bs-toggle="tab" href="#fight" role="tab" aria-controls="fight" aria-selected="false">{{__('links.fight')}}</a>
            </li>
            <li class="nav-item px-2" role="presentation">
                <a class="nav-link btn btn-success text-dark" data-bs-toggle="tab" href="#music" role="tab" aria-controls="music" aria-selected="false">{{__('links.music')}}</a>
            </li>
        </ul>
    </footer>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    let pages = [
        loadPage('/map/{{$game_id}}',       'map_cont'),
        loadPage('/characters/{{$game_id}}','chars_cont'),
        loadPage('/story/{{$game_id}}',     'story_cont'),
        loadPage('/music/{{$music_list}}',  'music_cont'),
        loadPage('/fight/{{$game_id}}',     'fight_cont'),
    ];
    loaded(pages);
});

function loadPage(url, container) {
    return $.ajax({
        url: url + '?play=true',
        success: function(response) { $('#' + container).html(response); },
        error: function() { $('#' + container).html('<h1>Смерт сторінки...</h1>'); }
    });
}

function loaded(pages) {
    Promise.all(pages).then(() => {
        console.log('Pages loaded');
        $('#pageLoader').fadeOut();
    }).catch(() => {
        console.error('Some page dont load :(');
        $('#pageLoader').fadeOut();
    });
}

window.addEventListener("beforeunload", function (e) {
    var confirmationMessage = "???";
    e.returnValue = confirmationMessage;
    return confirmationMessage;
});
</script>
@endsection