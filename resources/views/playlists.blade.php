@extends('layouts.layout')

@section('title', 'Плейлисти')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-4">
            <a data-bs-toggle="modal" data-bs-target="#addPlaylistModal">
                <div class="game-card" style="background-color: lightgreen;">
                    <div class="game-card-overlay">
                        <div><h3>Новий плейлист</h3></div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        @foreach($playlists as $playlist)
            <div class="col-md-4 mb-4">
                <a href="/music/{{$playlist->id}}" style="text-decoration: none">
                    <div class="game-card" style="background-image: url('https://img.youtube.com/vi/{{isset($playlist->music[0]) ? $playlist->music[mt_rand(0,count($playlist->music) - 1)]->youtube_url : ''}}/0.jpg');">
                        <div class="game-card-overlay">
                            <div><h3>{{$playlist->title}}</h3></div>
                            <div><h7>{{$playlist->description}}</h7></div>
                        </div>

                        <div class="flex position-absolute bottom-0 start-0 ms-2 mb-2">
                            @if($playlist->visible == 1)
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16">
                                    <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7 7 0 0 0-2.79.588l.77.771A6 6 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755q-.247.248-.517.486z"/>
                                    <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829"/>
                                    <path d="M3.35 5.47q-.27.24-.518.487A13 13 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7 7 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12z"/>
                                </svg>
                            @endif
                        </div>

                        <button onclick="changeList(event, '{{$playlist->title}}', '{{$playlist->description}}', '{{$playlist->id}}', '{{$playlist->visible}}')" data-bs-toggle="modal" data-bs-target="#changePlaylistModal" type="submit" class="btn btn-warning btn-sm position-absolute bottom-0 end-0 mb-2 me-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                                <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001m-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708z"/>
                            </svg>
                        </button>

                        <form method="POST" action="{{ route('playlist.destroy', ['id' => $playlist->id]) }}" class="position-absolute bottom-0 end-0 mb-2" onsubmit="return confirmDeletion(event, '{{$playlist->title}}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm me-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                    <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>

<div class="modal fade" id="addPlaylistModal" tabindex="-1" aria-labelledby="addPlaylistModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPlaylistModalLabel">Новий плейлист</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addPlaylistForm" method="POST" action="{{ route('playlist.add') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="playlistTitle" class="form-label">Назва</label>
                        <input type="text" class="form-control" id="playlistTitle" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="playlistDescription" class="form-label">Опис</label>
                        <textarea class="form-control" id="playlistDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3 form-check form-switch">
                        <label class="form-check-label" for="gameVisibility">Видимість</label>
                        <input class="form-check-input" type="checkbox" id="gameVisibility" name="visibility" checked>
                    </div>
                    <button type="submit" class="btn btn-success">Додати</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="changePlaylistModal" tabindex="-1" aria-labelledby="changePlaylistModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePlaylistModalLabel">...</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                    <form id="editform" method="POST" action="playlist/">
                        @csrf
                        @method('PUT')

                    <div class="mb-3">
                        <label for="playlistTitle" class="form-label">Назва</label>
                        <input type="text" class="form-control" id="chPlaylistTitle" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="playlistDescription" class="form-label">Опис</label>
                        <textarea class="form-control" id="chPlaylistDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3 form-check form-switch">
                        <label class="form-check-label" for="chGameVisibility">Видимість</label>
                        <input class="form-check-input" type="checkbox" id="chGameVisibility" name="visibility" checked>
                    </div>
                    <button type="submit" class="btn btn-warning">Змінити</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script>
        function confirmDeletion(event, title) {
            event.preventDefault();
            if (confirm('Ви точно хочете видалити плейлист "' + title + '" ?')) {
                event.target.submit();
            }
        }

        function changeList(event, title, descr, id, visible) {
            event.preventDefault();
            document.getElementById('editform').action = '/playlist/' + id;
            document.getElementById('changePlaylistModalLabel').innerHTML = 'Змінити плейлист "' + title + '"';
            document.getElementById('chPlaylistTitle').value = title;
            document.getElementById('chPlaylistDescription').value = descr;
            document.getElementById('chGameVisibility').checked = visible == 1 ? true : false;
        }
    </script>
@endsection
