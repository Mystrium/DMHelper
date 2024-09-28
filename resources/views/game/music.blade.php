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

    <div class="tab-content pt-4" style="background-color:lightgray">
        @foreach($categories as $categ)
            <div class="tab-pane fade show {{$loop->first?'active':''}}" id="pl{{$categ->id}}" role="tabpanel" aria-labelledby="playlist1-tab">
                <div class="d-flex overflow-auto py-3" style="white-space: nowrap;">
                    @foreach($musics as $music)
                        @if($music->music_category_id == $categ->id)
                            <div class="video-item mx-2 position-relative" style="width: 350px; height: 200px;">
                                <iframe class="music-video" width="350" height="200" src="https://www.youtube.com/embed/{{$music->youtube_url}}" frameborder="0"></iframe>
                                
                                <form method="POST" action="{{ route('music.destroy', ['id' => $music->id]) }}" class="position-absolute bottom-0 end-0 m-2" onsubmit="return confirmDeletion(event);">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endforeach

                    <a data-bs-toggle="modal" onclick="changeCateg({{$categ->id}})" data-bs-target="#addPlaylistModal">
                        <div class="game-card p-1" style="background-color: lightgreen; height: 200px">
                            <div class="game-card-overlay">
                                <div class="game-title">Додати пісню</div>
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
        @endforeach
    </div>

</div>

<div class="modal fade" id="addPlaylistModal" tabindex="-1" aria-labelledby="addPlaylistModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPlaylistModalLabel">Нова пісня</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('music.add', ['id' => $musicList->id]) }}">
                    @csrf
                    <div class="mb-3">
                        <label for="playlistTitle" class="form-label">Категорія</label>
                        <select class="form-select" id="categ_select" name="music_category_id" required>
                            @foreach($categories as $categ)
                                <option value="{{$categ->id}}">{{$categ->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Посилання на YouTube</label>
                        <input type="text" class="form-control" name="youtube_url" required>
                    </div>
                    <button type="submit" class="btn btn-success">Додати</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="text-center p-3">
    <button class="btn btn-success" onClick="controlVideo('pauseVideo');">Зробити тишу</button>
</div>
@endsection

@section('scripts')
    <script>
        function changeCateg(id) {
            document.getElementById('categ_select').value = id;
        }

        function confirmDeletion(event) {
            event.preventDefault();
            if (confirm('Ви точно хочете видалити цю пісню ?')) {
                event.target.submit();
            }
        }

        function controlVideo(vidFunc) {
            var iframe = document.getElementsByTagName("iframe");
            for (let i = 0; i < iframe.length; i++) {
                iframe[i].contentWindow.postMessage('{"event":"command","func":"' + vidFunc + '","args":""}',"*");
            }
        }

        const videos = document.querySelectorAll('.music-video');
        videos.forEach(video => {
            let src = video.src;
            video.src = src + '?enablejsapi=1';
        });
    </script>
@endsection
