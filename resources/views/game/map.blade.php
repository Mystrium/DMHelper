@extends('layouts.layout')

@section('title', 'Мапа')

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

        <a class="btn btn-success position-fixed bottom-0 start-0 m-2" href="/story/{{$game_id}}"><- Історія</a>

        @if(isset($maps[0]->id))
            <div class="position-absolute top-0 start-0 p-3 border rounded bg-light">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="option1">
                    <label class="form-check-label" for="option1">Сюжет</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" onclick="showMarkers(this)" checked>
                    <label class="form-check-label">Мітки</label>
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
                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addMapModal">Нова мапа</a></li>
                        @if(isset($maps[0]->id))
                            <li><a class="dropdown-item" onclick="getPosition()">Мітка</a></li>
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
                <h5 class="modal-title" id="addMapModalLabel">Нова мапа</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/addmap/{{$game_id}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Назва мапи</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Картинка</label>
                        <input type="file" class="form-control" name="map_file" required>
                    </div>
                    <button type="submit" data-bs-dismiss="modal"  class="btn btn-success">Додати</button>
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
                <h5 class="modal-title" id="addMapModalLabel">Нова мітка</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="marker_parrent_map" value="0">
                <input type="hidden" id="marker_x" name="x" value="0">
                <input type="hidden" id="marker_y" name="y" value="0">
                <div class="mb-3">
                    <label class="form-label">Назва мітки</label>
                    <input type="text" class="form-control" id="marker_title" name="title" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Опис мітки</label>
                    <textarea class="form-control" id="marker_text" name="text" required></textarea>
                </div>
                <button onclick="uploadMarker()" data-bs-dismiss="modal" class="btn btn-success">Додати</button>
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
    uninnit = true;
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

function placeMarker(mrk) {
    let percentX = (mrk.x / mapImage[mrk.map_id].width) * 100;
    let percentY = ((mrk.y - 8) / mapImage[mrk.map_id].height) * 100;

    const marker = document.createElement('div');
    marker.classList.add('map-marker');
    marker.id = "marker_" + mrk.id;
    marker.setAttribute('map_id', mrk.map_id);
    marker.style.left = `${percentX}%`;
    marker.style.top = `${percentY}%`;

    marker.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red" class="bi bi-pin" viewBox="0 0 16 16">
                            <path d="M4.146.146A.5.5 0 0 1 4.5 0h7a.5.5 0 0 1 .5.5c0 .68-.342 1.174-.646 1.479-.126.125-.25.224-.354.298v4.431l.078.048c.203.127.476.314.751.555C12.36 7.775 13 8.527 13 9.5a.5.5 0 0 1-.5.5h-4v4.5c0 .276-.224 1.5-.5 1.5s-.5-1.224-.5-1.5V10h-4a.5.5 0 0 1-.5-.5c0-.973.64-1.725 1.17-2.189A6 6 0 0 1 5 6.708V2.277a3 3 0 0 1-.354-.298C4.342 1.674 4 1.179 4 .5a.5.5 0 0 1 .146-.354m1.58 1.408-.002-.001zm-.002-.001.002.001A.5.5 0 0 1 6 2v5a.5.5 0 0 1-.276.447h-.002l-.012.007-.054.03a5 5 0 0 0-.827.58c-.318.278-.585.596-.725.936h7.792c-.14-.34-.407-.658-.725-.936a5 5 0 0 0-.881-.61l-.012-.006h-.002A.5.5 0 0 1 10 7V2a.5.5 0 0 1 .295-.458 1.8 1.8 0 0 0 .351-.271c.08-.08.155-.17.214-.271H5.14q.091.15.214.271a1.8 1.8 0 0 0 .37.282"/>
                        </svg>
                        
                        <div class="tooltip-card">
                            <div class="card">
                                <div class="card-body">
                                    @if(!request()->has('play'))
                                        <input type="text" class="form-control" id="change_marker_title_${mrk.id}" prev_text="${mrk.title}" name="title" value="${mrk.title}" required>
                                        <textarea style="width:200px" class="form-control my-2" id="change_marker_text_${mrk.id}" prev_text="${mrk.text}" name="text" required>${mrk.text}</textarea>
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
            showAlert('Поле задовге або пусте', 'warning');
        }
    }).catch(error => { console.error('Error:', error);});
}

function confirmDeletion(event) {
    event.preventDefault();
    if (confirm('Ви точно хочете видалити цю мапу ?')){ 
        document.getElementById('delete_form').action = '/map/' + currentMap;
        event.target.submit();
    }
}

function showMarkers(cheker) {
    if(cheker.checked) {
        document.querySelectorAll('.map-marker').forEach(marker => {
            marker.removeAttribute("hidden");
        });
    } else {
        document.querySelectorAll('.map-marker').forEach(marker => {
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
            showAlert('Мітку додано');
        } else 
            showAlert('Поле задовге або пусте', 'warning');
    }).catch(error => { console.error('Error:', error);});

    document.getElementById('marker_title').value = '';
    document.getElementById('marker_text').value = '';
}

function deleteMarker(btn) {
    const markerId = btn.getAttribute('data-id');
    const confirmed = confirm("Ви хочете видалити цю мітку ?");

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
                showAlert('Мітку видаленно');
            } else showAlert(data.message, 'warning');
        })
        .catch(error => console.error('Error:', error));
    }
};

function changeMarker(btn) {
    const markerId = btn.getAttribute('data-id');
    let changeTitle = document.getElementById('change_marker_title_' + markerId);
    let changeText = document.getElementById('change_marker_text_' + markerId);

    let formData = {
        id: markerId,
        title: changeTitle.value,
        text: changeText.value,
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
            showAlert('Маткер оновленно');
        } else {
            changeTitle.value = changeTitle.getAttribute('prev_text');
            changeText.innerText = changeText.getAttribute('prev_text');
            showAlert('Поле задовге або пусте', 'warning');
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
