@extends('layouts.layout')

@section('title', 'Бій')

@section('content')
    <div class="position-relative">
        <div id="mapContainer" class="border" style="height: 625px; overflow: hidden; position: relative;">
            <img id="mapImage" src="https://runefoundry.com/cdn/shop/products/ForestEncampment_digital_day_grid.jpg?v=1676584019" alt="Карта" style="width: 1600px; height: 800px; cursor: grab; position: absolute;">
        </div>

        <div class="position-absolute top-0 start-0 p-3">
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
