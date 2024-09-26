@extends('layouts.layout')

@section('title', 'Музика')

@section('content')
<div class="container-fluid pt-3 text-center">
    <h5>Плейлист назва</h5>

    <div class="pt-3" style="white-space: nowrap;">
        <ul class="nav nav-tabs w-100 justify-content-center" id="playlistTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="playlist1-tab" data-bs-toggle="tab" data-bs-target="#playlist1" type="button" role="tab" aria-controls="playlist1" aria-selected="true">
                <div class="playlist-item mx-2">
                        <img src="https://img.youtube.com/vi/vyg5jJrZ42s/0.jpg" width="200" height="150" alt="Плейлист 1">
                        <p>Плейлист 1</p>
                    </div>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="playlist2-tab" data-bs-toggle="tab" data-bs-target="#playlist2" type="button" role="tab" aria-controls="playlist2" aria-selected="false">
                <div class="playlist-item mx-2">
                        <img src="https://img.youtube.com/vi/6Em9tLXbhfo/0.jpg" width="200" height="150" alt="Плейлист 1">
                        <p>Плейлист 2</p>
                    </div>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="playlist2-tab" data-bs-toggle="tab" data-bs-target="#playlist3" type="button" role="tab" aria-controls="playlist2" aria-selected="false">
                <div class="playlist-item mx-2">
                        <img src="https://img.youtube.com/vi/D02-tXFkgCI/0.jpg" width="200" height="150" alt="Плейлист 1">
                        <p>Плейлист 3</p>
                    </div>
                </button>
            </li>
        </ul>
    </div>

    <div class="tab-content pt-4" style="background-color:lightgray">
        <div class="tab-pane fade show active" id="playlist1" role="tabpanel" aria-labelledby="playlist1-tab">
            <div class="d-flex overflow-auto py-3" style="white-space: nowrap;">
                <div class="video-item mx-2">
                    <iframe width="350" height="200" src="https://www.youtube.com/embed/vyg5jJrZ42s" frameborder="0"></iframe>
                    <p>Пісня 1</p>
                </div>
                <div class="video-item mx-2">
                    <iframe width="350" height="200" src="https://www.youtube.com/embed/6Em9tLXbhfo" frameborder="0"></iframe>
                    <p>Пісня 2</p>
                </div>
                <div class="video-item mx-2">
                    <iframe width="350" height="200" src="https://www.youtube.com/embed/D02-tXFkgCI" frameborder="0"></iframe>
                    <p>Пісня 3</p>
                </div>
                <div class="video-item mx-2">
                    <iframe width="350" height="200" src="https://www.youtube.com/embed/vyg5jJrZ42s" frameborder="0"></iframe>
                    <p>Пісня 1</p>
                </div>
                <div class="video-item mx-2">
                    <iframe width="350" height="200" src="https://www.youtube.com/embed/6Em9tLXbhfo" frameborder="0"></iframe>
                    <p>Пісня 2</p>
                </div>
                <div class="video-item mx-2">
                    <iframe width="350" height="200" src="https://www.youtube.com/embed/D02-tXFkgCI" frameborder="0"></iframe>
                    <p>Пісня 3</p>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="playlist2" role="tabpanel" aria-labelledby="playlist2-tab">
            <div class="d-flex overflow-auto py-3" style="white-space: nowrap;">
                <div class="video-item mx-2">
                    <iframe width="350" height="200" src="https://www.youtube.com/embed/6Em9tLXbhfo" frameborder="0"></iframe>
                    <p>Пісня 2</p>
                </div>
                <div class="video-item mx-2">
                    <iframe width="350" height="200" src="https://www.youtube.com/embed/vyg5jJrZ42s" frameborder="0"></iframe>
                    <p>Пісня 1</p>
                </div>
                <div class="video-item mx-2">
                    <iframe width="350" height="200" src="https://www.youtube.com/embed/D02-tXFkgCI" frameborder="0"></iframe>
                    <p>Пісня 3</p>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="playlist3" role="tabpanel" aria-labelledby="playlist2-tab">
            <div class="d-flex justify-content-center overflow-auto py-3" style="white-space: nowrap;">
                <div class="video-item mx-2">
                    <iframe width="350" height="200" src="https://www.youtube.com/embed/D02-tXFkgCI" frameborder="0"></iframe>
                    <p>Пісня 3</p>
                </div>
                <div class="video-item mx-2">
                    <iframe width="350" height="200" src="https://www.youtube.com/embed/vyg5jJrZ42s" frameborder="0"></iframe>
                    <p>Пісня 1</p>
                </div>
                <div class="video-item mx-2">
                    <iframe width="350" height="200" src="https://www.youtube.com/embed/6Em9tLXbhfo" frameborder="0"></iframe>
                    <p>Пісня 2</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
