@extends('layouts.layout')

@section('title', 'Мапа')

@section('content')
    <div class="position-relative">
        <div id="mapContainer" class="border" style="height: 650px; overflow: hidden; position: relative;">
            <div class="map-wrapper">
                <div class="map-container">
                    <img id="map-image"  src="/storage/maps/{{$maps[0]->file_name??''}}" draggable="false">
                    <div onclick="addMarkerHandler()" id="marker-container"></div>
                </div>
            </div>
        </div>

        @if(isset($maps[0]->id))
            <div class="position-absolute top-0 start-0 p-3 border rounded bg-light">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="option1">
                    <label class="form-check-label" for="option1">Сюжет</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" onclick="showMarkers(this)" checked>
                    <label class="form-check-label" for="option2">Мітки</label>
                </div>
            </div>
        @endif

        <div class="position-absolute top-0 end-0 p-3">
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

        @if(isset($maps[0]->id))
            <div class="position-absolute bottom-0 end-0 p-3">
                <form method="POST" action="{{ route('map.destroy', ['id' => $maps[0]->id ?? '0']) }}" class="dropdown-item" onsubmit="return confirmDeletion(event);">
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
            <div class="position-absolute bottom-0 start-50 translate-middle-x bg-light p-2 border rounded opacity-75">
                <div class="d-flex">
                    <input type="text" class="form-control" prev_text="{{$maps[0]->title}}" id="map_change_title_{{$maps[0]->id}}" value="{{$maps[0]->title}}">
                    <button onclick="changeMap({{$maps[0]->id}})" class="btn btn-warning ms-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                            <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001m-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708z"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </div>

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
                <form id="marker-upload-form">
                    @csrf
                    <input type="hidden" id="marker_parrent_map" value="{{$maps[0]->id??0}}">
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
                    <button type="submit" data-bs-dismiss="modal" class="btn btn-success">Додати</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="alert-container"></div>
@endsection
@section('scripts')
<script>
const mapWrapper = document.querySelector('.map-wrapper');
let mapImage = document.getElementById('map-image');
let markerContainer = document.getElementById('marker-container');

let offsetX = 0;
let offsetY = 0;

let scale = 1;
let maxZoom = 3;
let minZoom = 0.5;

mapWrapper.addEventListener('wheel', (event) => {
    event.preventDefault();

    const prevScale = scale;
    const zoomSpeed = 0.1;

    scale += zoomSpeed * (event.deltaY > 0 ? -1 : 1);
    scale = Math.min(maxZoom, Math.max(minZoom, scale));

    let deltaScale = scale / prevScale;

    const rect = mapImage.getBoundingClientRect();
    offsetX -= (event.clientX - rect.left) * (deltaScale - 1);
    offsetY -= (event.clientY - rect.top)  * (deltaScale - 1);

    setImageTransform(offsetX, offsetY, scale);
});

function placeMarker(mrk) {
    let percentX = (mrk.x / mapImage.width) * 100;
    let percentY = ((mrk.y - 8) / mapImage.height) * 100;

    const marker = document.createElement('div');
    marker.classList.add('map-marker');
    marker.id = "marker_" + mrk.id;
    marker.style.left = `${percentX}%`;
    marker.style.top = `${percentY}%`;

    marker.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red" class="bi bi-pin" viewBox="0 0 16 16">
                            <path d="M4.146.146A.5.5 0 0 1 4.5 0h7a.5.5 0 0 1 .5.5c0 .68-.342 1.174-.646 1.479-.126.125-.25.224-.354.298v4.431l.078.048c.203.127.476.314.751.555C12.36 7.775 13 8.527 13 9.5a.5.5 0 0 1-.5.5h-4v4.5c0 .276-.224 1.5-.5 1.5s-.5-1.224-.5-1.5V10h-4a.5.5 0 0 1-.5-.5c0-.973.64-1.725 1.17-2.189A6 6 0 0 1 5 6.708V2.277a3 3 0 0 1-.354-.298C4.342 1.674 4 1.179 4 .5a.5.5 0 0 1 .146-.354m1.58 1.408-.002-.001zm-.002-.001.002.001A.5.5 0 0 1 6 2v5a.5.5 0 0 1-.276.447h-.002l-.012.007-.054.03a5 5 0 0 0-.827.58c-.318.278-.585.596-.725.936h7.792c-.14-.34-.407-.658-.725-.936a5 5 0 0 0-.881-.61l-.012-.006h-.002A.5.5 0 0 1 10 7V2a.5.5 0 0 1 .295-.458 1.8 1.8 0 0 0 .351-.271c.08-.08.155-.17.214-.271H5.14q.091.15.214.271a1.8 1.8 0 0 0 .37.282"/>
                        </svg>
                        
                        <div class="tooltip-card">
                            <div class="card">
                                <div class="card-body">
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
                                </div>
                            </div>
                        </div>`;

    marker.style.transform = `translate(-50%, -50%) scale(${1 / scale})`;

    markerContainer.appendChild(marker);
}

let isDrag = false;
let mouseX = 0;
let mouseY = 0;
mapWrapper.addEventListener('mousedown', (event) => {
    isDrag = true;
    mouseX = event.clientX;
    mouseY = event.clientY;
});

mapWrapper.addEventListener('mousemove', (event) => {
    if(isDrag){
        offsetX += (event.clientX - mouseX);
        offsetY += (event.clientY - mouseY);

        mouseX = event.clientX;
        mouseY = event.clientY;

        setImageTransform(offsetX, offsetY, scale);
    }
});

mapWrapper.addEventListener('mouseup', (event) => { isDrag = false; });

window.addEventListener("load", (event) => {
    maxZoom = calcMaxZoom(mapImage, mapWrapper, 15);
    minZoom = calcMinZoom(mapImage, mapWrapper, 0.9);

    scale = minZoom;
    offsetX += (mapWrapper.clientWidth -  mapImage.naturalWidth  * scale) / 2;
    offsetY += (mapWrapper.clientHeight - mapImage.naturalHeight * scale) / 2;

    markerContainer.style.width = mapImage.naturalWidth + "px";
    markerContainer.style.height  = mapImage.naturalHeight + "px";

    setImageTransform(offsetX, offsetY, scale);

    let markers = @json($maps[0]->markers ?? []);
    markers.forEach(marker => { placeMarker(marker); });
});

function setImageTransform(x, y, scale){
    mapImage.style.left = x + "px";
    mapImage.style.top  = y + "px";
    mapImage.style.scale = scale;

    markerContainer.style.left = x + "px";
    markerContainer.style.top  = y + "px";
    markerContainer.style.scale = scale;

    document.querySelectorAll('.map-marker').forEach(marker => {
        marker.style.transform = `translate(-50%, -50%) scale(${1 / scale})`;
    });

    console.log("x:y = " + x + "|" + y);
}

function calcMaxZoom(image, wrapper, pixelDensity){
    const scaleByWidth = (image.naturalWidth * pixelDensity) / wrapper.clientWidth;
    const scaleByHeight = (image.naturalHeight * pixelDensity) / wrapper.clientHeight;

    return Math.max(scaleByWidth, scaleByHeight);
}

function calcMinZoom(image, wrapper, pixelDensity){
    const scaleByWidth = wrapper.clientWidth / image.naturalWidth  * pixelDensity;
    const scaleByHeight = wrapper.clientHeight / image.naturalHeight  * pixelDensity;

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
    if (confirm('Ви точно хочете видалити цю мапу ?')) event.target.submit();
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
    markerContainer.classList.add("crosshair");
}

function addMarkerHandler() {
    if(wait_click){
        const rect = markerContainer.getBoundingClientRect();
        const x = Math.round((event.clientX - rect.left) / scale);
        const y = Math.round((event.clientY - rect.top) / scale);

        document.getElementById('marker_x').value = x;
        document.getElementById('marker_y').value = y;

        $('#addMarkerModal').modal('show');

        markerContainer.classList.remove("crosshair");
        wait_click = false;
    }
}

document.getElementById('marker-upload-form').addEventListener('submit', function (e) {
    e.preventDefault();

    let formData = {
        map_id: document.getElementById('marker_parrent_map').value,
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
            showAlert('Мітку додано');
        } else 
            showAlert('Поле задовге або пусте', 'warning');
    }).catch(error => { console.error('Error:', error);});

    document.getElementById('marker_title').value = '';
    document.getElementById('marker_text').value = '';
});

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
            showAlert('Маткер оновленно');
        } else {
            changeTitle.value = changeTitle.getAttribute('prev_text');
            changeText.innerText = changeText.getAttribute('prev_text');
            showAlert('Поле задовге або пусте', 'warning');
        }
    }).catch(error => { console.error('Error:', error); });
}

function showAlert(message, type = 'success') {
    const alert = document.createElement('div');
    alert.style = "z-index: 1030";
    alert.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x`;
    alert.role = 'alert';
    alert.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;

    const container = document.getElementById('alert-container');
    container.appendChild(alert);

    setTimeout(() => {
        alert.classList.remove('show');
        alert.classList.add('fade');
        setTimeout(() => { container.removeChild(alert); }, 150);
    }, 2000);
}

</script>
@endsection
