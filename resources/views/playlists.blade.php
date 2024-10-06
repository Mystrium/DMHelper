@extends('layouts.layout')

@section('title', 'Плейлисти')

@section('content')
<div class="container my-5">
    <div class="row">
        @foreach($playlists as $playlist)
            <div class="col-md-4 md-4 position-relative">
                <a href="/music/{{$playlist->id}}" style="text-decoration: none">
                    <div class="game-card" style="background-image: url('https://img.youtube.com/vi/6Em9tLXbhfo/0.jpg');">
                        <div class="game-card-overlay">
                            <div><h3>{{$playlist->title}}</h3></div>
                            <div><h7>{{$playlist->description}}</h7></div>
                        </div>

                        <button onclick="changeList(event, '{{$playlist->title}}', '{{$playlist->description}}', '{{$playlist->id}}')" data-bs-toggle="modal" data-bs-target="#changePlaylistModal" type="submit" class="btn btn-warning btn-sm me-2 position-absolute bottom-0 end-0 mb-2 me-5">
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

        <div class="col-md-4 md-4">
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

        function changeList(event, title, descr, id) {
            event.preventDefault();
            document.getElementById('editform').action = 'playlist/' + id;
            document.getElementById('changePlaylistModalLabel').innerHTML = 'Змінити плейлист "' + title + '"';
            document.getElementById('chPlaylistTitle').value = title;
            document.getElementById('chPlaylistDescription').value = descr;
        }
    </script>
@endsection
