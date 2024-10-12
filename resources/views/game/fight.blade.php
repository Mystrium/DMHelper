@extends('layouts.layout')

@section('title', 'Бій')

@section('content')
<div class="position-relative">
    <div id="mapContainer" class="border" style="height: 640px; overflow: auto; position: relative;">
        <div class="map-container" style="position: relative;">
            <img class="map-image" id="battle-map" src="https://runefoundry.com/cdn/shop/products/ForestEncampment_digital_day_grid.jpg?v=1676584019" alt="Карта" style="width: 1600px; height: 820px;">
            <div class="map-image grid"></div>
        </div>
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
                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addPlayer">Гравець</a></li>
                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addEnemy">Ворог</a></li>
                <li><a class="dropdown-item" href="#">Декор</a></li>
            </ul>
        </div>
    </div>

    <div class="position-absolute bottom-0 start-50 translate-middle-x bg-light p-2 border rounded opacity-75">
        <p>Опис поточного місця або події.</p>
    </div>
</div>

<div class="modal fade" id="addPlayer" tabindex="-1" aria-labelledby="addPlaylistModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPlaylistModalLabel">Додати гравця</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Категорія</label>
                    <select class="form-select" id="player_id">
                        @foreach($players as $player)
                            <option value="{{$player['id']}}">{{$player['name']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ініціатива</label>
                    <input type="number" class="form-control" id="innitiative">
                </div>
                <button onclick="placePlayer()" data-bs-dismiss="modal" class="btn btn-success">Додати</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addEnemy" tabindex="-1" aria-labelledby="addPlaylistModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPlaylistModalLabel">Додати ворога</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Ім'я</label>
                    <input type="text" class="form-control" id="enemy_name">
                </div>
                <div class="mb-3">
                    <label class="form-label">ХП</label>
                    <input type="number" class="form-control" id="enemy_hp">
                </div>
                <div class="mb-3">
                    <label class="form-label">КЗ</label>
                    <input type="number" class="form-control" id="enemy_ac">
                </div>
                <div class="mb-3">
                    <label class="form-label">Ініціатива</label>
                    <input type="number" class="form-control" id="enemy_innit">
                </div>
                <button onclick="placeEnemy()" data-bs-dismiss="modal" class="btn btn-success">Додати</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
const fightMap = document.getElementById('battle-map');

const cellSize = 50;
fightMap.onload = function() {
    const gridOverlay = document.querySelector('.grid');
    const mapWidth = parseInt(fightMap.style.width);
    const mapHeight = parseInt(fightMap.style.height);

    const columns = Math.floor(mapWidth / cellSize);
    const rows = Math.floor(mapHeight / cellSize);

    gridOverlay.style.gridTemplateColumns = `repeat(${columns}, 1fr)`;
    gridOverlay.style.gridTemplateRows = `repeat(${rows}, 1fr)`;

    for (let i = 0; i < columns * rows; i++) {
        const cell = document.createElement('div');
        cell.classList.add('grid-cell');
        cell.style.width  = cellSize + 'px';
        cell.style.height = cellSize + 'px';
        cell.addEventListener('click', () => placeNpc(i));
        gridOverlay.appendChild(cell);
    }
};

let npc = @json($players);

let placing = 0;
function placePlayer() { placing = 1; }
function placeEnemy()  { placing = 2; }

function placeNpc(i) {
    if(placing == 1) {
        let pl_id = document.getElementById('player_id').value - 1;
        npc[pl_id].initiative += document.getElementById('innitiative').value * 1;
        npc[pl_id].pos = i;
        console.log(npc[pl_id]);
    }
    if(placing == 2) {
        let name = document.getElementById('enemy_name').value;
        let hp = document.getElementById('enemy_hp').value * 1;
        let innit = document.getElementById('enemy_innit').value * 1;
        npc.push({id: 10, name: name, initiative: innit, hp: hp, pos: i});
        console.log(npc);
    }
    placing = 0;
}


</script>
@endsection


<!-- 
    todo think
    todo unit type select ???
    todo dnd classes icons ???
        fight icons table ???
    todo active unit by initiative
        bottom unit info
    todo HP AC +-
    todo units movement
    todo unit attacks ???
    todo players seacrh ???
-->