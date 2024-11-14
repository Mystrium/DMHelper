@extends('layouts.layout')

@section('title', __('headers.maps'))

@section('content')
    <div class="position-relative">
        <div class="tab-content" id="mapTabsContent">
            @foreach($maps as $map)
                <div class="tab-pane {{$loop->first ? 'show active' : ''}}" id="map_tab_{{$map->id}}" role="tabpanel" aria-labelledby="tab_{{$map->id}}">
                    <div id="mapContainer_{{$map->id}}" style="height: 650px; overflow: hidden;">
                        <div class="map-wrapper" data-id="{{$map->id}}" id="map_wrapper_{{$map->id}}">
                            <div class="map-container" style="position: relative;">
                                <img id="map_image_{{$map->id}}" src="/storage/maps/{{$map->file_name}}" draggable="false" class="map-image">
                                <div onclick="addMarkerHandler()" id="marker_container_{{$map->id}}" class="map-image"></div>
                            </div>
                        </div>
                    </div>
                    <div class="position-absolute top-0 end-0 p-3">
                        <div class="dropdown">
                            <a class="dropdown-item" data-bs-toggle="dropdown" onclick="focusSearch()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                </svg>
                            </a>
                            <ul class="dropdown-menu" id="markers_{{$map->id}}" style="max-height: 500px; overflow-y: auto;">
                                <input type="text" class="form-control" id="marker_search" onkeyup="searchMarkers(this.value)">
                                @foreach ($map->markers as $marker)
                                    <li id="mark_{{$marker->id}}">
                                        <a class="dropdown-item search" onclick="highlightMarker({{$marker->id}})">{{$marker->title}}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <a class="btn btn-success position-fixed bottom-0 start-0 m-2" href="/story/{{$game_id}}"><- {{__('links.story')}}</a>

        @if(isset($maps[0]->id))
            <div class="position-absolute top-0 start-0 p-3 border rounded bg-light opacity-75">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" onclick="showMarkers(this, 1)" checked>
                    <label class="form-check-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pin" viewBox="0 0 16 16">
                            <path d="M4.146.146A.5.5 0 0 1 4.5 0h7a.5.5 0 0 1 .5.5c0 .68-.342 1.174-.646 1.479-.126.125-.25.224-.354.298v4.431l.078.048c.203.127.476.314.751.555C12.36 7.775 13 8.527 13 9.5a.5.5 0 0 1-.5.5h-4v4.5c0 .276-.224 1.5-.5 1.5s-.5-1.224-.5-1.5V10h-4a.5.5 0 0 1-.5-.5c0-.973.64-1.725 1.17-2.189A6 6 0 0 1 5 6.708V2.277a3 3 0 0 1-.354-.298C4.342 1.674 4 1.179 4 .5a.5.5 0 0 1 .146-.354m1.58 1.408-.002-.001zm-.002-.001.002.001A.5.5 0 0 1 6 2v5a.5.5 0 0 1-.276.447h-.002l-.012.007-.054.03a5 5 0 0 0-.827.58c-.318.278-.585.596-.725.936h7.792c-.14-.34-.407-.658-.725-.936a5 5 0 0 0-.881-.61l-.012-.006h-.002A.5.5 0 0 1 10 7V2a.5.5 0 0 1 .295-.458 1.8 1.8 0 0 0 .351-.271c.08-.08.155-.17.214-.271H5.14q.091.15.214.271a1.8 1.8 0 0 0 .37.282"/>
                        </svg>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" onclick="showMarkers(this, 2)" checked>
                    <label class="form-check-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pin" viewBox="0 0 16 16">
                            <path d="M14.778.085A.5.5 0 0 1 15 .5V8a.5.5 0 0 1-.314.464L14.5 8l.186.464-.003.001-.006.003-.023.009a12 12 0 0 1-.397.15c-.264.095-.631.223-1.047.35-.816.252-1.879.523-2.71.523-.847 0-1.548-.28-2.158-.525l-.028-.01C7.68 8.71 7.14 8.5 6.5 8.5c-.7 0-1.638.23-2.437.477A20 20 0 0 0 3 9.342V15.5a.5.5 0 0 1-1 0V.5a.5.5 0 0 1 1 0v.282c.226-.079.496-.17.79-.26C4.606.272 5.67 0 6.5 0c.84 0 1.524.277 2.121.519l.043.018C9.286.788 9.828 1 10.5 1c.7 0 1.638-.23 2.437-.477a20 20 0 0 0 1.349-.476l.019-.007.004-.002h.001M14 1.221c-.22.078-.48.167-.766.255-.81.252-1.872.523-2.734.523-.886 0-1.592-.286-2.203-.534l-.008-.003C7.662 1.21 7.139 1 6.5 1c-.669 0-1.606.229-2.415.478A21 21 0 0 0 3 1.845v6.433c.22-.078.48-.167.766-.255C4.576 7.77 5.638 7.5 6.5 7.5c.847 0 1.548.28 2.158.525l.028.01C9.32 8.29 9.86 8.5 10.5 8.5c.668 0 1.606-.229 2.415-.478A21 21 0 0 0 14 7.655V1.222z"/>
                        </svg>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" onclick="showMarkers(this, 3)" checked>
                    <label class="form-check-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pin" viewBox="0 0 16 16">
                            <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.56.56 0 0 0-.163-.505L1.71 6.745l4.052-.576a.53.53 0 0 0 .393-.288L8 2.223l1.847 3.658a.53.53 0 0 0 .393.288l4.052.575-2.906 2.77a.56.56 0 0 0-.163.506l.694 3.957-3.686-1.894a.5.5 0 0 0-.461 0z"/>
                        </svg>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" onclick="showMarkers(this, 4)" checked>
                    <label class="form-check-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pin" viewBox="0 0 16 16">
                            <path d="M6.95.435c.58-.58 1.52-.58 2.1 0l6.515 6.516c.58.58.58 1.519 0 2.098L9.05 15.565c-.58.58-1.519.58-2.098 0L.435 9.05a1.48 1.48 0 0 1 0-2.098zm1.4.7a.495.495 0 0 0-.7 0L1.134 7.65a.495.495 0 0 0 0 .7l6.516 6.516a.495.495 0 0 0 .7 0l6.516-6.516a.495.495 0 0 0 0-.7L8.35 1.134z"/>
                            <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286m1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94"/>
                        </svg>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" onclick="showMarkers(this, 5)" checked>
                    <label class="form-check-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pin" viewBox="0 0 16 16">
                            <path d="M6.95.435c.58-.58 1.52-.58 2.1 0l6.515 6.516c.58.58.58 1.519 0 2.098L9.05 15.565c-.58.58-1.519.58-2.098 0L.435 9.05a1.48 1.48 0 0 1 0-2.098zm1.4.7a.495.495 0 0 0-.7 0L1.134 7.65a.495.495 0 0 0 0 .7l6.516 6.516a.495.495 0 0 0 .7 0l6.516-6.516a.495.495 0 0 0 0-.7L8.35 1.134z"/>
                            <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                        </svg>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" onclick="showMarkers(this, 6)" checked>
                    <label class="form-check-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pin" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                        </svg>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" onclick="showMarkers(this, 7)" checked>
                    <label class="form-check-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pin" viewBox="0 0 16 16">
                            <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/>
                        </svg>
                    </label>
                </div>
            </div>
        @endif

        @if(!request()->has('play'))
            <div class="position-absolute top-0 end-0 p-3 mt-5">
                <div class="dropdown">
                    <a class="bg-transparent text-black pb-2" data-bs-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                        </svg>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addMapModal">{{__('labels.newmap')}}</a></li>
                        @if(isset($maps[0]->id))
                            <li><a class="dropdown-item" onclick="getPosition()">{{__('labels.newmarker')}}</a></li>
                        @endif
                    </ul>
                </div>
            </div>
        @endif

        @if(isset($maps[0]->id) && !request()->has('play'))
            <div class="position-absolute bottom-0 end-0 p-3">
                <form id="delete_form" method="POST" action="{{ route('map.destroy', ['id' => $maps[0]->id ?? '0']) }}" class="dropdown-item" onsubmit="return confirmDeletion(event);">
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

        @if(isset($maps[0]->id))
            <div class="position-absolute bottom-0 start-50 translate-middle-x bg-transparent p-2 opacity-75 w-75">
                <ul class="nav nav-tabs justify-content-center" id="mapTabs" role="tablist">
                    @foreach ($maps as $map)
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{$loop->first?'active':''}}" id="map1-tab" onclick="changeActiveMap({{$map->id}})" data-bs-toggle="tab" data-bs-target="#map_tab_{{$map->id}}" role="tab" aria-controls="map1" aria-selected="{{$loop->first?'true':'false'}}">
                                @if(!request()->has('play'))
                                    <div class="d-flex">
                                        <input style="width:100px" type="text" class="form-control" prev_text="{{$map->title}}" id="map_change_title_{{$map->id}}" value="{{$map->title}}">
                                        <button onclick="changeMap({{$map->id}})" class="btn btn-warning ms-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                                                <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001m-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708z"/>
                                            </svg>
                                        </button>
                                    </div>
                                @else
                                    <p>{{$map->title}}</p>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

@if(!request()->has('play'))
<!-- ___________  map modal  ________ -->
<div class="modal fade" id="addMapModal" tabindex="-1" aria-labelledby="addMapModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMapModalLabel">{{__('labels.newmap')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/addmap/{{$game_id}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">{{__('fields.title')}}</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{__('fields.image')}}</label>
                        <input type="file" class="form-control" name="map_file" required>
                    </div>
                    <button type="submit" data-bs-dismiss="modal"  class="btn btn-success">{{__('buttons.add')}}</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- __________ marker modal ________ -->
<div class="modal fade" id="addMarkerModal" tabindex="-1" aria-labelledby="addMapModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMapModalLabel">{{__('labels.newmarker')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="marker_parrent_map" value="0">
                <input type="hidden" id="marker_x" name="x" value="0">
                <input type="hidden" id="marker_y" name="y" value="0">
                <div class="mb-3">
                    <label class="form-label">{{__('fields.title')}}</label>
                    <input type="text" class="form-control" id="marker_title" name="title" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{__('fields.descr')}}</label>
                    <textarea class="form-control" id="marker_text" name="text" required></textarea>
                </div>
                <label class="form-label">{{__('fields.type')}}</label>
                <div class="mb-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="marker_type" checked id="pin" value="1">
                        <label class="form-check-label" for="pin">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pin" viewBox="0 0 16 16">
                                <path d="M4.146.146A.5.5 0 0 1 4.5 0h7a.5.5 0 0 1 .5.5c0 .68-.342 1.174-.646 1.479-.126.125-.25.224-.354.298v4.431l.078.048c.203.127.476.314.751.555C12.36 7.775 13 8.527 13 9.5a.5.5 0 0 1-.5.5h-4v4.5c0 .276-.224 1.5-.5 1.5s-.5-1.224-.5-1.5V10h-4a.5.5 0 0 1-.5-.5c0-.973.64-1.725 1.17-2.189A6 6 0 0 1 5 6.708V2.277a3 3 0 0 1-.354-.298C4.342 1.674 4 1.179 4 .5a.5.5 0 0 1 .146-.354m1.58 1.408-.002-.001zm-.002-.001.002.001A.5.5 0 0 1 6 2v5a.5.5 0 0 1-.276.447h-.002l-.012.007-.054.03a5 5 0 0 0-.827.58c-.318.278-.585.596-.725.936h7.792c-.14-.34-.407-.658-.725-.936a5 5 0 0 0-.881-.61l-.012-.006h-.002A.5.5 0 0 1 10 7V2a.5.5 0 0 1 .295-.458 1.8 1.8 0 0 0 .351-.271c.08-.08.155-.17.214-.271H5.14q.091.15.214.271a1.8 1.8 0 0 0 .37.282"/>
                            </svg>
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="marker_type" id="flag" value="2">
                        <label class="form-check-label" for="flag">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-flag" viewBox="0 0 16 16">
                                <path d="M14.778.085A.5.5 0 0 1 15 .5V8a.5.5 0 0 1-.314.464L14.5 8l.186.464-.003.001-.006.003-.023.009a12 12 0 0 1-.397.15c-.264.095-.631.223-1.047.35-.816.252-1.879.523-2.71.523-.847 0-1.548-.28-2.158-.525l-.028-.01C7.68 8.71 7.14 8.5 6.5 8.5c-.7 0-1.638.23-2.437.477A20 20 0 0 0 3 9.342V15.5a.5.5 0 0 1-1 0V.5a.5.5 0 0 1 1 0v.282c.226-.079.496-.17.79-.26C4.606.272 5.67 0 6.5 0c.84 0 1.524.277 2.121.519l.043.018C9.286.788 9.828 1 10.5 1c.7 0 1.638-.23 2.437-.477a20 20 0 0 0 1.349-.476l.019-.007.004-.002h.001M14 1.221c-.22.078-.48.167-.766.255-.81.252-1.872.523-2.734.523-.886 0-1.592-.286-2.203-.534l-.008-.003C7.662 1.21 7.139 1 6.5 1c-.669 0-1.606.229-2.415.478A21 21 0 0 0 3 1.845v6.433c.22-.078.48-.167.766-.255C4.576 7.77 5.638 7.5 6.5 7.5c.847 0 1.548.28 2.158.525l.028.01C9.32 8.29 9.86 8.5 10.5 8.5c.668 0 1.606-.229 2.415-.478A21 21 0 0 0 14 7.655V1.222z"/>
                            </svg>
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="marker_type" id="star" value="3">
                        <label class="form-check-label" for="star">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                                <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.56.56 0 0 0-.163-.505L1.71 6.745l4.052-.576a.53.53 0 0 0 .393-.288L8 2.223l1.847 3.658a.53.53 0 0 0 .393.288l4.052.575-2.906 2.77a.56.56 0 0 0-.163.506l.694 3.957-3.686-1.894a.5.5 0 0 0-.461 0z"/>
                            </svg>
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="marker_type" id="quest" value="4">
                        <label class="form-check-label" for="quest">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-question-diamond" viewBox="0 0 16 16">
                                <path d="M6.95.435c.58-.58 1.52-.58 2.1 0l6.515 6.516c.58.58.58 1.519 0 2.098L9.05 15.565c-.58.58-1.519.58-2.098 0L.435 9.05a1.48 1.48 0 0 1 0-2.098zm1.4.7a.495.495 0 0 0-.7 0L1.134 7.65a.495.495 0 0 0 0 .7l6.516 6.516a.495.495 0 0 0 .7 0l6.516-6.516a.495.495 0 0 0 0-.7L8.35 1.134z"/>
                                <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286m1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94"/>
                            </svg>
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="marker_type" id="warn" value="5">
                        <label class="form-check-label" for="warn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-exclamation-diamond" viewBox="0 0 16 16">
                                <path d="M6.95.435c.58-.58 1.52-.58 2.1 0l6.515 6.516c.58.58.58 1.519 0 2.098L9.05 15.565c-.58.58-1.519.58-2.098 0L.435 9.05a1.48 1.48 0 0 1 0-2.098zm1.4.7a.495.495 0 0 0-.7 0L1.134 7.65a.495.495 0 0 0 0 .7l6.516 6.516a.495.495 0 0 0 .7 0l6.516-6.516a.495.495 0 0 0 0-.7L8.35 1.134z"/>
                                <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                            </svg>
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="marker_type" id="info" value="6">
                        <label class="form-check-label" for="info">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                            </svg>
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="marker_type" id="heart" value="7">
                        <label class="form-check-label" for="heart">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                                <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/>
                            </svg>
                        </label>
                    </div>
                </div>
                <button onclick="uploadMarker()" data-bs-dismiss="modal" class="btn btn-success">{{__('buttons.add')}}</button>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
@section('scripts')
<script>
let currentMap = {{$maps[0]->id ?? 0}};
function changeActiveMap(id) { 
    currentMap = id;
    document.getElementById('marker_search').value = '';
    searchMarkers('');
}

let mapWrapper = {};
let mapImage = {};
let markerContainer = {};
let offset = {};
let maxZoom = {};
let minZoom = {};

let isDrag = false;
let mouseX = 0;
let mouseY = 0;

let uninnit = true;
setTimeout(function() {
    if(uninnit)
        initializeMaps();
}, 3000);

window.addEventListener("load", (event) => {
    uninnit = false;
    initializeMaps();
});

function initializeMaps() {
    document.querySelectorAll('.map-wrapper').forEach((wrapper) => {
        const mapId = wrapper.getAttribute('data-id');
        
        mapWrapper[mapId] = wrapper;
        mapImage[mapId] = wrapper.querySelector('.map-container img');
        markerContainer[mapId] = wrapper.querySelector('.map-container div');

        offset[mapId] = { x: 0, y: 0 };

        maxZoom[mapId] = calcMaxZoom(mapImage[mapId], 15);
        minZoom[mapId] = calcMinZoom(mapImage[mapId], 0.7);

        centerMap(mapId, minZoom[mapId]);
        wrapperListeners(mapId);
    });

    let maps = @json($maps ?? []);
    maps.forEach(map => { 
        map.markers.forEach(marker => { 
            placeMarker(marker); 
        });
    });
}

function centerMap(id, scale){
    offset[id].x = (window.innerWidth - mapImage[id].naturalWidth * scale) / 2;
    offset[id].y = (window.innerHeight * 0.8 - mapImage[id].naturalHeight * scale) / 2;

    markerContainer[id].style.width  = mapImage[id].naturalWidth  + "px";
    markerContainer[id].style.height = mapImage[id].naturalHeight + "px";

    setImageTransform(offset[id].x, offset[id].y, scale, id);
}

function wrapperListeners(id){
    mapWrapper[id].addEventListener('wheel', (event) => {
        event.preventDefault();

        let scale = mapImage[id].style.scale * 1;
        const prevScale = scale;
        const zoomSpeed = 0.1;

        scale += zoomSpeed * (event.deltaY > 0 ? -1 : 1);

        scale = Math.min(maxZoom[id], Math.max(minZoom[id], scale));
        let deltaScale = scale / prevScale;

        const rect = mapImage[id].getBoundingClientRect();
        offset[id].x -= (event.clientX - rect.left) * (deltaScale - 1);
        offset[id].y -= (event.clientY - rect.top)  * (deltaScale - 1);

        setImageTransform(offset[id].x, offset[id].y, scale, id);
    });

    mapWrapper[id].addEventListener('mousedown', (event) => {
        isDrag = true;
        mouseX = event.clientX;
        mouseY = event.clientY;
    });

    mapWrapper[id].addEventListener('mousemove', (event) => {
        if(isDrag){
            offset[id].x += (event.clientX - mouseX);
            offset[id].y += (event.clientY - mouseY);

            mouseX = event.clientX;
            mouseY = event.clientY;

            let scale = mapImage[id].style.scale * 1;
            setImageTransform(offset[id].x, offset[id].y, scale, id);
        }
    });

    mapWrapper[id].addEventListener('mouseup', (event) => { 
        isDrag = false;
        mouseX = 0;
        mouseY = 0;
    });
}

function getMarkerByType(type) {
    switch(type * 1) {
        case 1:
            return '<path d="M4.146.146A.5.5 0 0 1 4.5 0h7a.5.5 0 0 1 .5.5c0 .68-.342 1.174-.646 1.479-.126.125-.25.224-.354.298v4.431l.078.048c.203.127.476.314.751.555C12.36 7.775 13 8.527 13 9.5a.5.5 0 0 1-.5.5h-4v4.5c0 .276-.224 1.5-.5 1.5s-.5-1.224-.5-1.5V10h-4a.5.5 0 0 1-.5-.5c0-.973.64-1.725 1.17-2.189A6 6 0 0 1 5 6.708V2.277a3 3 0 0 1-.354-.298C4.342 1.674 4 1.179 4 .5a.5.5 0 0 1 .146-.354m1.58 1.408-.002-.001zm-.002-.001.002.001A.5.5 0 0 1 6 2v5a.5.5 0 0 1-.276.447h-.002l-.012.007-.054.03a5 5 0 0 0-.827.58c-.318.278-.585.596-.725.936h7.792c-.14-.34-.407-.658-.725-.936a5 5 0 0 0-.881-.61l-.012-.006h-.002A.5.5 0 0 1 10 7V2a.5.5 0 0 1 .295-.458 1.8 1.8 0 0 0 .351-.271c.08-.08.155-.17.214-.271H5.14q.091.15.214.271a1.8 1.8 0 0 0 .37.282"/>';
            break;
        case 2:
            return '<path d="M14.778.085A.5.5 0 0 1 15 .5V8a.5.5 0 0 1-.314.464L14.5 8l.186.464-.003.001-.006.003-.023.009a12 12 0 0 1-.397.15c-.264.095-.631.223-1.047.35-.816.252-1.879.523-2.71.523-.847 0-1.548-.28-2.158-.525l-.028-.01C7.68 8.71 7.14 8.5 6.5 8.5c-.7 0-1.638.23-2.437.477A20 20 0 0 0 3 9.342V15.5a.5.5 0 0 1-1 0V.5a.5.5 0 0 1 1 0v.282c.226-.079.496-.17.79-.26C4.606.272 5.67 0 6.5 0c.84 0 1.524.277 2.121.519l.043.018C9.286.788 9.828 1 10.5 1c.7 0 1.638-.23 2.437-.477a20 20 0 0 0 1.349-.476l.019-.007.004-.002h.001M14 1.221c-.22.078-.48.167-.766.255-.81.252-1.872.523-2.734.523-.886 0-1.592-.286-2.203-.534l-.008-.003C7.662 1.21 7.139 1 6.5 1c-.669 0-1.606.229-2.415.478A21 21 0 0 0 3 1.845v6.433c.22-.078.48-.167.766-.255C4.576 7.77 5.638 7.5 6.5 7.5c.847 0 1.548.28 2.158.525l.028.01C9.32 8.29 9.86 8.5 10.5 8.5c.668 0 1.606-.229 2.415-.478A21 21 0 0 0 14 7.655V1.222z"/>';
            break;
        case 3:
            return '<path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.56.56 0 0 0-.163-.505L1.71 6.745l4.052-.576a.53.53 0 0 0 .393-.288L8 2.223l1.847 3.658a.53.53 0 0 0 .393.288l4.052.575-2.906 2.77a.56.56 0 0 0-.163.506l.694 3.957-3.686-1.894a.5.5 0 0 0-.461 0z"/>';
            break;
        case 4:
            return `<path d="M6.95.435c.58-.58 1.52-.58 2.1 0l6.515 6.516c.58.58.58 1.519 0 2.098L9.05 15.565c-.58.58-1.519.58-2.098 0L.435 9.05a1.48 1.48 0 0 1 0-2.098zm1.4.7a.495.495 0 0 0-.7 0L1.134 7.65a.495.495 0 0 0 0 .7l6.516 6.516a.495.495 0 0 0 .7 0l6.516-6.516a.495.495 0 0 0 0-.7L8.35 1.134z"/>
                        <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286m1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94"/>`;
            break;
        case 5:
            return `<path d="M6.95.435c.58-.58 1.52-.58 2.1 0l6.515 6.516c.58.58.58 1.519 0 2.098L9.05 15.565c-.58.58-1.519.58-2.098 0L.435 9.05a1.48 1.48 0 0 1 0-2.098zm1.4.7a.495.495 0 0 0-.7 0L1.134 7.65a.495.495 0 0 0 0 .7l6.516 6.516a.495.495 0 0 0 .7 0l6.516-6.516a.495.495 0 0 0 0-.7L8.35 1.134z"/>
                        <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>`;
            break;
        case 6:
            return `<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                        <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>`;
            break;
        case 7:
            return '<path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/>';
            break;
        default:
            return '<path d="M4.146.146A.5.5 0 0 1 4.5 0h7a.5.5 0 0 1 .5.5c0 .68-.342 1.174-.646 1.479-.126.125-.25.224-.354.298v4.431l.078.048c.203.127.476.314.751.555C12.36 7.775 13 8.527 13 9.5a.5.5 0 0 1-.5.5h-4v4.5c0 .276-.224 1.5-.5 1.5s-.5-1.224-.5-1.5V10h-4a.5.5 0 0 1-.5-.5c0-.973.64-1.725 1.17-2.189A6 6 0 0 1 5 6.708V2.277a3 3 0 0 1-.354-.298C4.342 1.674 4 1.179 4 .5a.5.5 0 0 1 .146-.354m1.58 1.408-.002-.001zm-.002-.001.002.001A.5.5 0 0 1 6 2v5a.5.5 0 0 1-.276.447h-.002l-.012.007-.054.03a5 5 0 0 0-.827.58c-.318.278-.585.596-.725.936h7.792c-.14-.34-.407-.658-.725-.936a5 5 0 0 0-.881-.61l-.012-.006h-.002A.5.5 0 0 1 10 7V2a.5.5 0 0 1 .295-.458 1.8 1.8 0 0 0 .351-.271c.08-.08.155-.17.214-.271H5.14q.091.15.214.271a1.8 1.8 0 0 0 .37.282"/>';
            break;
    }
}

function placeMarker(mrk) {
    let percentX = (mrk.x / mapImage[mrk.map_id].width) * 100;
    let percentY = ((mrk.y - 8) / mapImage[mrk.map_id].height) * 100;

    const marker = document.createElement('div');
    marker.classList.add('map-marker');
    marker.classList.add('map_type_' + mrk.type);
    marker.id = "marker_" + mrk.id;
    marker.setAttribute('map_id', mrk.map_id);
    marker.style.left = `${percentX}%`;
    marker.style.top = `${percentY}%`;

    mark_svg = getMarkerByType(mrk.type);

    marker.innerHTML = `<svg id="marker_svg_${mrk.id}" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red" class="bi bi-pin" viewBox="0 0 16 16">
                            ${mark_svg}
                        </svg>
                        
                        <div class="tooltip-card">
                            <div class="card">
                                <div class="card-body">
                                    @if(!request()->has('play'))
                                        <input type="text" class="form-control" id="change_marker_title_${mrk.id}" prev_text="${mrk.title}" name="title" value="${mrk.title}" required>
                                        <textarea style="width:300px" class="form-control my-2" id="change_marker_text_${mrk.id}" prev_text="${mrk.text}" name="text" required>${mrk.text}</textarea>
                                        
                                        <div class="mb-3">
                                            <div class="form-check form-check-inline">
                                                <input data-prev="${mrk.type}" class="form-check-input" type="radio" name="marker_type_${mrk.id}" ${mrk.type * 1 == 1 ? 'checked' : ''} id="pin" value="1">
                                                <label class="form-check-label" for="pin">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="bi bi-pin" viewBox="0 0 16 16">
                                                        <path d="M4.146.146A.5.5 0 0 1 4.5 0h7a.5.5 0 0 1 .5.5c0 .68-.342 1.174-.646 1.479-.126.125-.25.224-.354.298v4.431l.078.048c.203.127.476.314.751.555C12.36 7.775 13 8.527 13 9.5a.5.5 0 0 1-.5.5h-4v4.5c0 .276-.224 1.5-.5 1.5s-.5-1.224-.5-1.5V10h-4a.5.5 0 0 1-.5-.5c0-.973.64-1.725 1.17-2.189A6 6 0 0 1 5 6.708V2.277a3 3 0 0 1-.354-.298C4.342 1.674 4 1.179 4 .5a.5.5 0 0 1 .146-.354m1.58 1.408-.002-.001zm-.002-.001.002.001A.5.5 0 0 1 6 2v5a.5.5 0 0 1-.276.447h-.002l-.012.007-.054.03a5 5 0 0 0-.827.58c-.318.278-.585.596-.725.936h7.792c-.14-.34-.407-.658-.725-.936a5 5 0 0 0-.881-.61l-.012-.006h-.002A.5.5 0 0 1 10 7V2a.5.5 0 0 1 .295-.458 1.8 1.8 0 0 0 .351-.271c.08-.08.155-.17.214-.271H5.14q.091.15.214.271a1.8 1.8 0 0 0 .37.282"/>
                                                    </svg>
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="marker_type_${mrk.id}" ${mrk.type * 1 == 2 ? 'checked' : ''} id="flag" value="2">
                                                <label class="form-check-label" for="flag">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="bi bi-flag" viewBox="0 0 16 16">
                                                        <path d="M14.778.085A.5.5 0 0 1 15 .5V8a.5.5 0 0 1-.314.464L14.5 8l.186.464-.003.001-.006.003-.023.009a12 12 0 0 1-.397.15c-.264.095-.631.223-1.047.35-.816.252-1.879.523-2.71.523-.847 0-1.548-.28-2.158-.525l-.028-.01C7.68 8.71 7.14 8.5 6.5 8.5c-.7 0-1.638.23-2.437.477A20 20 0 0 0 3 9.342V15.5a.5.5 0 0 1-1 0V.5a.5.5 0 0 1 1 0v.282c.226-.079.496-.17.79-.26C4.606.272 5.67 0 6.5 0c.84 0 1.524.277 2.121.519l.043.018C9.286.788 9.828 1 10.5 1c.7 0 1.638-.23 2.437-.477a20 20 0 0 0 1.349-.476l.019-.007.004-.002h.001M14 1.221c-.22.078-.48.167-.766.255-.81.252-1.872.523-2.734.523-.886 0-1.592-.286-2.203-.534l-.008-.003C7.662 1.21 7.139 1 6.5 1c-.669 0-1.606.229-2.415.478A21 21 0 0 0 3 1.845v6.433c.22-.078.48-.167.766-.255C4.576 7.77 5.638 7.5 6.5 7.5c.847 0 1.548.28 2.158.525l.028.01C9.32 8.29 9.86 8.5 10.5 8.5c.668 0 1.606-.229 2.415-.478A21 21 0 0 0 14 7.655V1.222z"/>
                                                    </svg>
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="marker_type_${mrk.id}" ${mrk.type * 1 == 3 ? 'checked' : ''} id="star" value="3">
                                                <label class="form-check-label" for="star">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                                                        <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.56.56 0 0 0-.163-.505L1.71 6.745l4.052-.576a.53.53 0 0 0 .393-.288L8 2.223l1.847 3.658a.53.53 0 0 0 .393.288l4.052.575-2.906 2.77a.56.56 0 0 0-.163.506l.694 3.957-3.686-1.894a.5.5 0 0 0-.461 0z"/>
                                                    </svg>
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="marker_type_${mrk.id}" ${mrk.type * 1 == 4 ? 'checked' : ''} id="quest" value="4">
                                                <label class="form-check-label" for="quest">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="bi bi-question-diamond" viewBox="0 0 16 16">
                                                        <path d="M6.95.435c.58-.58 1.52-.58 2.1 0l6.515 6.516c.58.58.58 1.519 0 2.098L9.05 15.565c-.58.58-1.519.58-2.098 0L.435 9.05a1.48 1.48 0 0 1 0-2.098zm1.4.7a.495.495 0 0 0-.7 0L1.134 7.65a.495.495 0 0 0 0 .7l6.516 6.516a.495.495 0 0 0 .7 0l6.516-6.516a.495.495 0 0 0 0-.7L8.35 1.134z"/>
                                                        <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286m1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94"/>
                                                    </svg>
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="marker_type_${mrk.id}" ${mrk.type * 1 == 5 ? 'checked' : ''} id="warn" value="5">
                                                <label class="form-check-label" for="warn">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="bi bi-exclamation-diamond" viewBox="0 0 16 16">
                                                        <path d="M6.95.435c.58-.58 1.52-.58 2.1 0l6.515 6.516c.58.58.58 1.519 0 2.098L9.05 15.565c-.58.58-1.519.58-2.098 0L.435 9.05a1.48 1.48 0 0 1 0-2.098zm1.4.7a.495.495 0 0 0-.7 0L1.134 7.65a.495.495 0 0 0 0 .7l6.516 6.516a.495.495 0 0 0 .7 0l6.516-6.516a.495.495 0 0 0 0-.7L8.35 1.134z"/>
                                                        <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                                                    </svg>
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="marker_type_${mrk.id}" ${mrk.type * 1 == 6 ? 'checked' : ''} id="info" value="6">
                                                <label class="form-check-label" for="info">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                                        <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                                                    </svg>
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="marker_type_${mrk.id}" ${mrk.type * 1 == 7 ? 'checked' : ''} id="heart" value="7">
                                                <label class="form-check-label" for="heart">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                                                        <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/>
                                                    </svg>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="d-flex">
                                            <button class="btn btn-warning btn-sm me-1" data-id="${mrk.id}" onclick="changeMarker(this)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                                                    <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001m-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708z"/>
                                                </svg>
                                            </button>

                                            <button class="btn btn-danger btn-sm marker_delete" data-id="${mrk.id}" onclick="deleteMarker(this)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                    <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                                </svg>
                                            </button>
                                        </div>
                                    @else
                                        <h6 class="text-center">${mrk.title}</h6>
                                        <p>${mrk.text}</p>
                                    @endif
                                </div>
                            </div>
                        </div>`;

    marker.style.transform = `translate(-50%, -50%) scale(${1 / mapImage[mrk.map_id].style.scale})`;

    markerContainer[mrk.map_id].appendChild(marker);
}

function setImageTransform(x, y, scale, id){
    mapImage[id].style.left = x + "px";
    mapImage[id].style.top  = y + "px";
    mapImage[id].style.scale = scale;

    markerContainer[id].style.left = x + "px";
    markerContainer[id].style.top  = y + "px";
    markerContainer[id].style.scale = scale;

    document.querySelectorAll('.map-marker').forEach(marker => {
        if(marker.getAttribute('map_id') == currentMap)
            marker.style.transform = `translate(-50%, -50%) scale(${1 / scale})`;
    });
}

function calcMaxZoom(image, pixelDensity){
    const scaleByWidth = (image.naturalWidth * pixelDensity) / window.innerWidth;
    const scaleByHeight = (image.naturalHeight * pixelDensity) / window.innerHeight;

    return Math.max(scaleByWidth, scaleByHeight);
}

function calcMinZoom(image, pixelDensity){
    const scaleByWidth = window.innerWidth / image.naturalWidth  * pixelDensity;
    const scaleByHeight = window.innerHeight / image.naturalHeight  * pixelDensity;

    return Math.min(scaleByWidth, scaleByHeight);
}

function changeMap(mapId){
    let changeTitle = document.getElementById('map_change_title_' + mapId);
    let formData = {
        map_id: mapId,
        title: changeTitle.value,
        _token: document.querySelector('input[name=_token]').value
    };

    fetch('/updatemap', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': formData._token
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if(data.success){
            changeTitle.setAttribute('prev_text', changeTitle.value);
            showAlert('Назву змінено');
        } else {
            changeTitle.value = changeTitle.getAttribute('prev_text');
            showAlert("{{__('messages.warning.invalid')}}", 'warning');
        }
    }).catch(error => { console.error('Error:', error);});
}

function confirmDeletion(event) {
    event.preventDefault();
    if (confirm('{{__('messages.delete.map')}}  ?')){ 
        document.getElementById('delete_form').action = '/map/' + currentMap;
        event.target.submit();
    }
}

function showMarkers(cheker, type) {
    if(cheker.checked) {
        document.querySelectorAll('.map_type_' + type).forEach(marker => {
            marker.removeAttribute("hidden");
        });
    } else {
        document.querySelectorAll('.map_type_' + type).forEach(marker => {
            marker.setAttribute("hidden", true);
        });
    }
}

let wait_click = false;
function getPosition(){ 
    wait_click = true;
    markerContainer[currentMap].classList.add("crosshair");
}

function addMarkerHandler() {
    if(wait_click){
        const rect = markerContainer[currentMap].getBoundingClientRect();
        const scale = markerContainer[currentMap].style.scale;
        const x = Math.round((event.clientX - rect.left) / scale);
        const y = Math.round((event.clientY - rect.top) / scale);

        document.getElementById('marker_x').value = x;
        document.getElementById('marker_y').value = y;
        document.getElementById('marker_parrent_map').value = currentMap;

        $('#addMarkerModal').modal('show');

        markerContainer[currentMap].classList.remove("crosshair");
        wait_click = false;
    }
}

function uploadMarker() {
    let map_id = document.getElementById('marker_parrent_map').value;
    let formData = {
        map_id: map_id,
        title: document.getElementById('marker_title').value,
        text: document.getElementById('marker_text').value,
        type: document.querySelector('input[name="marker_type"]:checked').value,
        x: document.getElementById('marker_x').value,
        y: document.getElementById('marker_y').value,

        _token: document.querySelector('input[name=_token]').value
    };

    fetch('/addmarker', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': formData._token
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.marker) {
            placeMarker(data.marker);
            document.getElementById("markers_" + map_id).innerHTML += 
                `<li id="mark_${data.marker.id}"><a class="dropdown-item search" onclick="highlightMarker(${data.marker.id})">${data.marker.title}</a></li>`;
            showAlert("{{__('messages.added.marker')}}");
        } else 
            showAlert("{{__('messages.warning.invalid')}}", 'warning');
    }).catch(error => { console.error('Error:', error);});

    document.getElementById('marker_title').value = '';
    document.getElementById('marker_text').value = '';
}

function deleteMarker(btn) {
    const markerId = btn.getAttribute('data-id');
    const confirmed = confirm("{{__('messages.delete.marker')}}  ?");

    if (confirmed) {
        fetch(`/marker/${markerId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById("marker_" + markerId).remove();
                document.getElementById("mark_" + markerId).remove();
                showAlert("{{__('messages.deleted.marker')}}");
            } else showAlert(data.message, 'warning');
        })
        .catch(error => console.error('Error:', error));
    }
};

function changeMarker(btn) {
    const markerId  = btn.getAttribute('data-id');
    let changeTitle = document.getElementById('change_marker_title_' + markerId);
    let changeText  = document.getElementById('change_marker_text_' + markerId);
    let changeType  = document.querySelector(`input[name="marker_type_${markerId}"]:checked`);

    let formData = {
        id: markerId,
        title: changeTitle.value,
        text: changeText.value,
        type: changeType.value,
        _token: document.querySelector('input[name=_token]').value
    };

    fetch('/updatemarker', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': formData._token
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if(data.success){
            changeTitle.setAttribute('prev_text', changeTitle.value);
            changeText.setAttribute('prev_text', changeText.value);
            document.querySelector("#mark_" + markerId + " a").innerHTML = changeTitle.value;
            document.querySelector("#marker_svg_" + markerId).innerHTML = getMarkerByType(changeType.value);            
            showAlert("{{__('messages.updated.marker')}}");
        } else {
            changeTitle.value = changeTitle.getAttribute('prev_text');
            changeText.innerText = changeText.getAttribute('prev_text');
            changeType.checked = false;
            checkers = document.querySelectorAll(`input[name="marker_type_${markerId}"]`);
            console.log(checkers);
            checkers[checkers[0].dataset.prev * 1 - 1].checked = true;

            showAlert("{{__('messages.warning.invalid')}}", 'warning');
        }
    }).catch(error => { console.error('Error:', error); });
}

function focusSearch() {
    document.getElementById('marker_search').focus();
}

function searchMarkers(srch) {
    const filter = srch.toLowerCase();
    const a = document.getElementsByClassName("search");
    for (let i = 0; i < a.length; i++) {
        txtValue = a[i].textContent || a[i].innerText;
        if (txtValue.toLowerCase().indexOf(filter) > -1) {
            a[i].style.display = "";
        } else {
            a[i].style.display = "none";
        }
    }
}

function highlightMarker(id) {
    const marker = document.getElementById("marker_" + id).getBoundingClientRect();
    const map = mapImage[currentMap].getBoundingClientRect();

    offset[currentMap].x = map.left - marker.left + window.innerWidth / 2;
    offset[currentMap].y = map.top - marker.top + window.innerHeight / 2 - 100;

    setImageTransform(offset[currentMap].x, offset[currentMap].y, mapImage[currentMap].style.scale * 1, currentMap);
}

</script>
@endsection

<!-- 
    todo
        map markers types
            svg
    -->