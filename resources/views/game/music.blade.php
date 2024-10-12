@extends('layouts.layout')

@section('title', 'Музика')

@section('content')
<div class="container-fluid pt-3 text-center">
    <h5>{{$musicList->title}}</h5>
    <p>{{$musicList->description}}</p>

    <div style="white-space: nowrap;">
        <ul class="nav nav-tabs w-100 justify-content-center" id="playlistTabs" role="tablist">
            @foreach($categories as $categ)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{$loop->first?'active':''}}" id="playlist1-tab" data-bs-toggle="tab" data-bs-target="#pl{{$categ->id}}" type="button" role="tab" aria-selected="{{$loop->first?'true':'false'}}">

                        @foreach($musics as $music)
                            @if($music->music_category_id==$categ->id)
                                @php $url = $music->youtube_url @endphp
                                @break
                            @endif
                        @endforeach

                        <div class="playlist-item mx-2">
                            <img src="https://img.youtube.com/vi/{{$url??''}}/0.jpg" width="200" height="150">
                            <p>{{$categ->title}}</p>
                        </div>

                        @php $url = '' @endphp
                    </button>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="tab-content pt-2" style="background-color:lightgray">
        @foreach($categories as $categ)
            <div class="tab-pane fade show {{$loop->first?'active':''}}" id="pl{{$categ->id}}" role="tabpanel" aria-labelledby="playlist1-tab">
                <div class="d-flex overflow-auto pt-4 pb-4" style="white-space: nowrap;" id="music_category_{{$categ->id}}">
                    @foreach($musics as $music)
                        @if($music->music_category_id == $categ->id)
                            <div id="music_{{$music->id}}" class="video-item mx-2 position-relative" style="width: 350px; height: 200px;">
                                <iframe class="music-video" width="350" height="200" src="https://www.youtube.com/embed/{{$music->youtube_url}}" frameborder="0"></iframe>
                                @if(!request()->has('play'))
                                    <button class="position-absolute bottom-0 end-0 m-2 btn btn-danger btn-sm marker_delete" data-id="{{$music->id}}" onclick="deleteMusic(this)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        @endif
                    @endforeach

                    @if(!request()->has('play'))
                        <a id="music_add_{{$categ->id}}" data-bs-toggle="modal" onclick="changeCateg({{$categ->id}})" data-bs-target="#addPlaylistModal">
                            <div class="game-card p-1" style="background-color: lightgreen; height: 200px">
                                <div class="game-card-overlay">
                                    <div><h3>Додати пісню</h3></div>
                                    <div>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endif

                </div>
            </div>
        @endforeach
    </div>
</div>

@if(!request()->has('play'))
    <div class="modal fade" id="addPlaylistModal" tabindex="-1" aria-labelledby="addPlaylistModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPlaylistModalLabel">Нова пісня</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="music-upload-form">
                        @csrf
                        <input type="hidden" id="music_list_id" value="{{$musicList->id}}">
                        <div class="mb-3">
                            <label class="form-label">Категорія</label>
                            <select class="form-select" id="music_category_id">
                                @foreach($categories as $categ)
                                    <option value="{{$categ->id}}">{{$categ->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Посилання на YouTube</label>
                            <input type="text" class="form-control" id="youtube_url" required>
                        </div>
                        <button onclick="uploadMusic()" data-bs-dismiss="modal" class="btn btn-success">Додати</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="text-center p-3">
    <button class="btn btn-success" onClick="controlVideo('pauseVideo');">Зробити тишу</button>
</div>
@endsection

@section('scripts')
<script>
    const videos = document.querySelectorAll('.music-video');
    videos.forEach(video => { video.src += '?enablejsapi=1'; });

    function changeCateg(id) { document.getElementById('music_category_id').value = id; }

    function uploadMusic() {
        let formData = {
            music_list_id: document.getElementById('music_list_id').value,
            music_category_id: document.getElementById('music_category_id').value,
            youtube_url: document.getElementById('youtube_url').value,
            _token: document.querySelector('input[name=_token]').value
        };

        fetch('/addmusic', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': formData._token
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.music) {
                placeMusic(data.music);
                showAlert('Пісню додано');
            } else
                showAlert('Посилання задовге або пусте', 'warning');
        }).catch(error => { console.error('Error:', error);});

        document.getElementById('youtube_url').value = '';
    }

    function placeMusic(msc){
        const music = document.createElement('div');
        music.classList.add('video-item', 'mx-2', 'position-relative');
        music.id = "music_" + msc.id;

        music.innerHTML = `<iframe class="music-video" width="350" height="200" src="https://www.youtube.com/embed/${msc.youtube_url}" frameborder="0"></iframe>
                                
                            <button class="position-absolute bottom-0 end-0 m-2 btn btn-danger btn-sm marker_delete" data-id="${msc.id}" onclick="deleteMusic(this)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                    <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                </svg>
                            </button>`;

        let music_div = document.getElementById('music_category_' + msc.music_category_id);
        let music_btn = document.getElementById('music_add_' + msc.music_category_id);
        music_div.insertBefore(music, music_btn);
    }

    function deleteMusic(btn) {
        const musicId = btn.getAttribute('data-id');
        const confirmed = confirm("Ви хочете видалити цю пісню ?");

        if (confirmed) {
            fetch(`/music/${musicId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById("music_" + musicId).remove();
                    showAlert('Пісню видаленно');
                } else showAlert(data.message, 'warning');
            })
            .catch(error => console.error('Error:', error));
        }
    };

    function controlVideo(vidFunc) {
        var iframe = document.getElementsByTagName("iframe");
        for (let i = 0; i < iframe.length; i++) {
            iframe[i].contentWindow.postMessage('{"event":"command","func":"' + vidFunc + '","args":""}',"*");
        }
    }
</script>
@endsection