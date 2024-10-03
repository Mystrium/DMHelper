@extends('layouts.layout')

@section('title', 'Мапа')

@section('content')
    <div class="position-relative">
        <div id="mapContainer" class="border" style="height: 1000px; overflow: hidden; position: relative;">
            <div class="map-wrapper">
                <div class="map-container">
                    <img id="map-image" src="https://media.wizards.com/2015/images/dnd/resources/20151117_Sword-Coast-Map.jpg" alt="Карта" draggable="false" style="width: 1000px; height: 500px; cursor: grab; position: absolute;">
                </div>
            </div>
        </div>

        <div class="position-absolute top-0 start-0 p-3 border rounded bg-light">
            <div class="form-check">
                <input class="form-check-input" onclick="test()" type="checkbox" id="option1">
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
const mapWrapper = document.querySelector('.map-wrapper');
const mapContainer = document.querySelector('.map-container');
let mapImage = document.getElementById('map-image');

let offsetX = 0;
let offsetY = 0;

let scale = 1;
const maxZoom = calcMaxZoom(mapImage, mapWrapper, 10);

mapWrapper.addEventListener('wheel', (event) => {
    event.preventDefault();

    const prevScale = scale;
    const zoomSpeed = 0.1;
    scale += zoomSpeed * -(event.deltaY / Math.abs(event.deltaY));

    if(scale < 0.3) scale = 0.3;
    if(scale > maxZoom)  scale = maxZoom;

    let deltaScale = scale / prevScale;
    if (isNaN(deltaScale)) deltaScale = 1;

    const rect = mapImage.getBoundingClientRect();
    offsetX -= (event.clientX - rect.left) * (deltaScale - 1);
    offsetY -= (event.clientY - rect.top)  * (deltaScale - 1);

    setImageTransform(offsetX, offsetY, scale);
});

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

function setImageTransform(x, y, scale){
    mapImage.style.left = x + "px";
    mapImage.style.top  = y + "px";
    mapImage.style.scale = scale;
    console.log('x = ' + x + "   y = " + y + "  sc = " + scale);
}

function calcMaxZoom(image, wrapper,  pixelDensity = 1){
    const imageWidth = image.naturalWidth;
    const imageHeight = image.naturalHeight;
    const wrapperWidth = wrapper.clientWidth;
    const wrapperHeight = wrapper.clientHeight;

    const scaleByWidth = (imageWidth * pixelDensity) / wrapperWidth;
    const scaleByHeight = (imageHeight * pixelDensity) / wrapperHeight;

    return Math.max(scaleByWidth, scaleByHeight);
}

</script>
@endsection
