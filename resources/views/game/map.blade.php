@extends('layouts.layout')

@section('title', 'Мапа')

@section('content')
    <div class="position-relative">
        <div id="mapContainer" class="border" style="height: 650px; overflow: hidden; position: relative;">
            <div class="map-wrapper">
                <div class="map-container">
                    <img id="mapImage" src="https://media.wizards.com/2015/images/dnd/resources/20151117_Sword-Coast-Map.jpg" alt="Карта" draggable="false" style="width: 1600px; height: 800px; cursor: grab; position: absolute;">
                </div>
            </div>
        </div>

        <div class="position-absolute top-0 start-0 p-3 border rounded bg-light">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="option1">
                <label class="form-check-label" for="option1">Сюжет</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="option2">
                <label class="form-check-label" for="option2">Мітки</label>
            </div>
        </div>

        <div class="position-absolute bottom-0 start-50 translate-middle-x bg-light p-2 border rounded opacity-75">
            <p>Опис поточного місця або події.</p>
        </div>
    </div>
@endsection


@section('scripts')
<script>
let mapImage = document.getElementById('mapImage');
    let isPanning = false, startX = 0, startY = 0, offsetX = 0, offsetY = 0;

    mapImage.addEventListener('mousedown', (e) => {
        isPanning = true;
        startX = e.clientX - offsetX;
        startY = e.clientY - offsetY;
        mapImage.style.cursor = 'grabbing';
    });

    document.addEventListener('mousemove', (e) => {
        if (!isPanning) return;
        offsetX = e.clientX - startX;
        offsetY = e.clientY - startY;
        mapImage.style.transform = `translate(${offsetX}px, ${offsetY}px)`;
    });

    document.addEventListener('mouseup', () => {
        isPanning = false;
        mapImage.style.cursor = 'grab';
    });

    // Масштабування через колесо миші
    let scale = 1;
    mapImage.addEventListener('wheel', (e) => {
        e.preventDefault();
        const delta = e.deltaY > 0 ? -0.1 : 0.1;
        scale = Math.min(Math.max(0.5, scale + delta), 2);
        mapImage.style.transform = `scale(${scale}) translate(${offsetX}px, ${offsetY}px)`;
    });
</script>
@endsection
