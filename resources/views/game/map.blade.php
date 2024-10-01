@extends('layouts.layout')

@section('title', 'Мапа')

@section('content')
    <div class="position-relative">
        <div id="mapContainer" class="border" style="height: 650px; overflow: hidden; position: relative;">
            <div class="map-wrapper">
                <div class="map-container">
                    <img id="map-image" src="https://media.wizards.com/2015/images/dnd/resources/20151117_Sword-Coast-Map.jpg" alt="Карта" draggable="false" style="width: 1600px; height: 800px; cursor: grab; position: absolute;">
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
document.addEventListener('DOMContentLoaded', function() {
    const mapContainer = document.querySelector('.map-container');
    const mapImage = document.getElementById('map-image');

    let isDragging = false;
    let startX, startY, initialX = 0, initialY = 0;
    let translateX = 0, translateY = 0;
    let scale = 1;
    const scaleStep = 0.1;

    // Переміщення мапи
    mapContainer.addEventListener('mousedown', (e) => {
        isDragging = true;
        mapContainer.style.cursor = 'grabbing';
        startX = e.clientX;
        startY = e.clientY;
        const style = window.getComputedStyle(mapContainer);
        const matrix = new WebKitCSSMatrix(style.transform);
        translateX = matrix.m41;
        translateY = matrix.m42;
    });

    mapContainer.addEventListener('mousemove', (e) => {
        if (isDragging) {
            const dx = e.clientX - startX;
            const dy = e.clientY - startY;
            mapContainer.style.transform = `translate(${translateX + dx}px, ${translateY + dy}px) scale(${scale})`;
        }
    });

    mapContainer.addEventListener('mouseup', () => {
        isDragging = false;
        mapContainer.style.cursor = 'grab';
    });

    mapContainer.addEventListener('mouseleave', () => {
        isDragging = false;
        mapContainer.style.cursor = 'grab';
    });

    // Масштабування мапи
    mapContainer.addEventListener('wheel', (e) => {
        e.preventDefault();

        const rect = mapContainer.getBoundingClientRect();
        const offsetX = (e.clientX - rect.left);
        const offsetY = (e.clientY - rect.top);

        const delta = e.deltaY > 0 ? -scaleStep : scaleStep;
        const newScale = Math.max(0.1, scale + delta);

        // Коригування translate, щоб масштабувалось відносно центру курсора
        translateX += offsetX * (1 - newScale / scale);
        translateY += offsetY * (1 - newScale / scale);

        scale = newScale;
        mapContainer.style.transform = `translate(${translateX}px, ${translateY}px) scale(${scale})`;
    });

    mapContainer.addEventListener('click', (e) => {
        const rect = mapContainer.getBoundingClientRect();
        const x = (e.clientX - rect.left) / scale; // Врахування масштабу
        const y = (e.clientY - rect.top) / scale; // Врахування масштабу

        const point = document.createElement('div');
        point.className = 'point';
        point.style.left = `${x}px`;
        point.style.top = `${y}px`;
        mapContainer.appendChild(point);
    });
    
});
</script>
@endsection
