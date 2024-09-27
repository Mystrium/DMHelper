@extends('layouts.layout')

@section('title', 'Бій')

@section('content')
    <div class="position-relative">
        <div id="mapContainer" class="border" style="height: 640px; overflow: hidden; position: relative;">
            <img id="mapImage" src="https://runefoundry.com/cdn/shop/products/ForestEncampment_digital_day_grid.jpg?v=1676584019" alt="Карта" style="width: 1600px; height: 820px; position: absolute;">
        </div>

        <div class="position-absolute top-0 start-0 p-3">
            <div class="dropdown">
                <a class="bg-transparent text-black pb-2" data-bs-toggle="dropdown">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-map" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15.817.113A.5.5 0 0 1 16 .5v14a.5.5 0 0 1-.402.49l-5 1a.5.5 0 0 1-.196 0L5.5 15.01l-4.902.98A.5.5 0 0 1 0 15.5v-14a.5.5 0 0 1 .402-.49l5-1a.5.5 0 0 1 .196 0L10.5.99l4.902-.98a.5.5 0 0 1 .415.103M10 1.91l-4-.8v12.98l4 .8zm1 12.98 4-.8V1.11l-4 .8zm-6-.8V1.11l-4 .8v12.98z"/>
                    </svg>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Ліс</a></li>
                    <li><a class="dropdown-item" href="#">Місто</a></li>
                    <li><a class="dropdown-item" href="#">Зима</a></li>
                </ul>
            </div>
        </div>

        <div class="position-absolute top-0 end-0 p-3">
            <div class="dropdown">
                <a class="bg-transparent text-black pb-2" data-bs-toggle="dropdown">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                    </svg>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Гравець</a></li>
                    <li><a class="dropdown-item" href="#">Ворог</a></li>
                    <li><a class="dropdown-item" href="#">Декор</a></li>
                </ul>
            </div>
        </div>

        <div class="position-absolute bottom-0 start-50 translate-middle-x bg-light p-2 border rounded opacity-75">
            <p>Опис поточного місця або події.</p>
        </div>
    </div>
@endsection
