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

    <div class="position-absolute top-0 end-0 p-4">
        <div class="dropdown">
            <a class="bg-transparent text-black pb-2" data-bs-toggle="dropdown">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                </svg>
            </a>
            <ul class="dropdown-menu">
                <li id="add_player_li"><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addPlayer">Гравець</a></li>
                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addEnemy">Ворог</a></li>
                <li><a class="dropdown-item" href="#">Декор</a></li>
            </ul>
        </div>
    </div>

    <div class="position-absolute bottom-0 end-0 p-4">
        <button class="btn btn-success" onclick="nextTurn()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-right" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M6 8a.5.5 0 0 0 .5.5h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L12.293 7.5H6.5A.5.5 0 0 0 6 8m-2.5 7a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5"/>
            </svg>
        </button>
    </div>

    <div class="position-absolute bottom-0 start-50 translate-middle-x bg-light p-2 border rounded opacity-75 pb-2">
        <span id="curr_name"></span>
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/>
            </svg>
            <span id="curr_hp"></span>
        </div>
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-shield" viewBox="0 0 16 16">
                <path d="M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533q.18.085.293.118a1 1 0 0 0 .101.025 1 1 0 0 0 .1-.025q.114-.034.294-.118c.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.8 11.8 0 0 1-2.517 2.453 7 7 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7 7 0 0 1-1.048-.625 11.8 11.8 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 63 63 0 0 1 5.072.56"/>
            </svg>
            <span id="curr_ac"></span>
        </div>
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lightning-charge" viewBox="0 0 16 16">
                <path d="M11.251.068a.5.5 0 0 1 .227.58L9.677 6.5H13a.5.5 0 0 1 .364.843l-8 8.5a.5.5 0 0 1-.842-.49L6.323 9.5H3a.5.5 0 0 1-.364-.843l8-8.5a.5.5 0 0 1 .615-.09zM4.157 8.5H7a.5.5 0 0 1 .478.647L6.11 13.59l5.732-6.09H9a.5.5 0 0 1-.478-.647L9.89 2.41z"/>
            </svg>
            <span id="curr_innit"></span>
        </div>
        <span id="curr_state"></span>
        <button onclick="startAtack()" class="btn btn-warning">A</button>
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
                        @foreach($players as $id =>  $player)
                            <option value="{{$id}}">{{$player['name']}}</option>
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


<div class="modal fade" id="attackModal" tabindex="-1" aria-labelledby="addPlaylistModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPlaylistModalLabel">Атака</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Хіти</label>
                    <input type="number" class="form-control" id="damage">
                </div>
                <div class="mb-3">
                    <label class="form-label">Стан</label>
                    <select id="state_select" class="form-control">
                        <option value="0">-</option>
                        <option value="blinded">Осліплений</option>
                        <option value="stunned">Оглушений</option>
                        <option value="dying">Присмерті</option>
                    </select>
                </div>
                <button onclick="attackNpc()" data-bs-dismiss="modal" class="btn btn-success">Атакувати</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> <!-- ??? -->
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
        cell.setAttribute('data-index', i);
        cell.addEventListener('click', () => placeNpc(i));
        gridOverlay.appendChild(cell);
    }
};

let npc = @json($players);
let moves = [];

let placing = 0;
function placePlayer() { placing = 1; }
function placeEnemy()  { placing = 2; }
function placeNpc(i) {
    if(placing == 1) {
        if(document.querySelector(`.grid-cell[data-index="${i}"]`).innerHTML == ''){
            let pl_id = document.getElementById('player_id').value * 1;
            npc[pl_id].initiative += document.getElementById('innitiative').value * 1;

            $(`#player_id option[value='${pl_id}']`).remove();
            if($('#player_id option').length == 0)
                document.getElementById('add_player_li').style.display = 'none'

            document.getElementById('innitiative').value = '';
            npc[pl_id].pos = i;
            npc[pl_id].player = 1;
            npc[pl_id].state = '';
            renderNpcOnGrid(pl_id);
            placing = 0;
        }
    }
    if(placing == 2) {
        if(document.querySelector(`.grid-cell[data-index="${i}"]`).innerHTML == ''){
            let name = document.getElementById('enemy_name').value;
            let hp = document.getElementById('enemy_hp').value * 1;
            let ac = document.getElementById('enemy_ac').value * 1;
            let innit = document.getElementById('enemy_innit').value * 1;

            let id = Object.keys(npc).length + 1;
            do { id++; } while (npc[id] != null)

            npc[id] = {name: name, initiative: innit, hp: hp, armor: ac, pos: i, player: 0, state: ''};
            renderNpcOnGrid(id);
            placing = 0;
        }
    }
    if(placing == 3)
        placeNpcOnCell(i);
}

function renderNpcOnGrid(id) {
    let cell = document.querySelector(`.grid-cell[data-index="${npc[id].pos}"]`);
    let npcDiv = document.createElement('div');
    npcDiv.classList.add('npc', 'map-marker');
    npcDiv.innerHTML = `${npc[id].name}
                        <div class="tooltip-card">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="text-center">${npc[id].name}</h6>
                                    <div>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                                            <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/>
                                        </svg>
                                        <span id="hp_tip_${id}">${npc[id].hp}</span>
                                    </div>
                                    <div>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-shield" viewBox="0 0 16 16">
                                            <path d="M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533q.18.085.293.118a1 1 0 0 0 .101.025 1 1 0 0 0 .1-.025q.114-.034.294-.118c.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.8 11.8 0 0 1-2.517 2.453 7 7 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7 7 0 0 1-1.048-.625 11.8 11.8 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 63 63 0 0 1 5.072.56"/>
                                        </svg>
                                        <span id="ac_tip_${id}">${npc[id].armor}</span>
                                    </div>
                                    <div>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lightning-charge" viewBox="0 0 16 16">
                                            <path d="M11.251.068a.5.5 0 0 1 .227.58L9.677 6.5H13a.5.5 0 0 1 .364.843l-8 8.5a.5.5 0 0 1-.842-.49L6.323 9.5H3a.5.5 0 0 1-.364-.843l8-8.5a.5.5 0 0 1 .615-.09zM4.157 8.5H7a.5.5 0 0 1 .478.647L6.11 13.59l5.732-6.09H9a.5.5 0 0 1-.478-.647L9.89 2.41z"/>
                                        </svg>
                                        <span id="tip_${id}">${npc[id].initiative}</span>
                                    </div>
                                    <span id="state_tip_${id}"></span>
                                </div>
                            </div>
                        </div>`;

    npcDiv.setAttribute('data-id', id);

    npcDiv.addEventListener('click', (event) => {
            event.stopPropagation();
            activateNpc(id);
        });
    cell.appendChild(npcDiv);

    if(placing == 1 || placing == 2) {
        moves.push({id:id, innit:npc[id].initiative});
        moves.sort((a,b) => b.innit - a.innit);
    }
    console.log(moves);
    highlightNpc();
}

function highlightNpc() {
    let id = moves[currentTurn].id
    const npcs = document.querySelectorAll('.npc');
    npcs.forEach(n => n.classList.remove('active'));

    let activeNpcCell = document.querySelector(`.grid-cell[data-index="${npc[id].pos}"] .npc`);
    activeNpcCell.classList.add('active');

    document.getElementById('curr_name').innerText = npc[id].name;
    document.getElementById('curr_hp').innerText = npc[id].hp;
    document.getElementById('curr_ac').innerText = npc[id].armor;
    document.getElementById('curr_innit').innerText = npc[id].initiative;
    document.getElementById('curr_state').innerText = npc[id].state;
}

let currentTurn = 0;
function nextTurn() {
    currentTurn++;
    if (currentTurn >= moves.length) currentTurn = 0;
    highlightNpc();
}

let attack = false;
function startAtack(){
    attack = true;
}

let activeNpcId = null;
let currentTarget = null;

function activateNpc(id) {
    if(attack) {
        currentTarget = id;
        console.log("Вибрана ціль:", npc[id].name);

        $('#attackModal').modal('show'); // modal is not a function...
    } else {
        activeNpcId = id;
        placing = 3;
        const npcDiv = document.querySelector(`.npc[data-id="${activeNpcId}"]`);
        npcDiv.style.left = `${event.pageX - cellSize / 2}px`;
        npcDiv.style.top =  `${event.pageY - 40}px`;
        document.addEventListener('mousemove', moveNpcWithMouse);
    }
}

function moveNpcWithMouse(event) {
    if (activeNpcId !== null) {
        const npcDiv = document.querySelector(`.npc[data-id="${activeNpcId}"]`);
        npcDiv.style.left = `${event.pageX - $('#mapContainer').scrollLeft() - cellSize / 2}px`;
        npcDiv.style.top =  `${event.pageY - $('#mapContainer').scrollTop() - 40 }px`;
    }
    console.log($('.map-container').scrollTop());
}

function placeNpcOnCell(index) {
    if (activeNpcId !== null) {
        let oldCell = document.querySelector(`.grid-cell[data-index="${npc[activeNpcId].pos}"]`);
        if (oldCell)
            oldCell.innerHTML = '';
        
        npc[activeNpcId].pos = index;
        renderNpcOnGrid(activeNpcId);

        document.removeEventListener('mousemove', moveNpcWithMouse);
        activeNpcId = null;
        placing = 0;
    }
}

function attackNpc() {
    let damage = document.getElementById('damage').value;
    npc[currentTarget].hp -= damage;
    document.getElementById('hp_tip_' + currentTarget).innerText = npc[currentTarget].hp;

    let state = document.getElementById('state_select').value;
    document.getElementById('state_tip_' + currentTarget).innerText = state;
    npc[currentTarget].state = state;

    if(npc[currentTarget].hp <= 0 && !npc[currentTarget].player){
        document.querySelector(`.npc[data-id="${currentTarget}"]`).remove();
        moves.splice(moves.findIndex(el => el.id == currentTarget), 1);
    }

    if(currentTarget == moves[currentTurn].id)
        renderNpcOnGrid(currentTarget);

    attack = false;
}

</script>
@endsection

<!-- 
    todo think
    todo unit type select ???
    todo dnd classes icons ???
        fight icons table ???
    todo players seacrh ???
-->