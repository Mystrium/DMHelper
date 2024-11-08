@extends('layouts.layout')

@section('title', 'Історія')

@section('content')
<div class="container-fluid">
    <div id="graph-container" style="position: relative; height: 2000px; width: 100%;"></div>

    <a class="btn btn-success position-fixed top-0 start-0 mt-5" href="/characters/{{$gameId}}"><- {{__('links.map')}}</a>
    <a class="btn btn-success position-fixed bottom-0 start-0 m-2" href="/map/{{$gameId}}"><- {{__('links.map')}}</a>
    <a class="btn btn-success position-fixed bottom-0 end-0 m-2" href="/story/{{$gameId}}?play=true">{{__('links.preview')}}</a>

    <div class="form-container position-fixed bottom-0 start-50 translate-middle-x text-center mb-1 w-50">
        <div class="form-group">
            <input type="text" class="form-control text-center" id="block_title" required>
        </div>
        <div class="form-group">
            <textarea class="form-control" id="block_text" rows="3" required></textarea>
        </div>

        <div class="form-group">
            <select id="nextNodeSelect" class="form-control select2" multiple="multiple">
                @foreach ($blocks as $node)
                    <option value="{{ $node['id'] }}">{{ $node['title'] }}</option>
                @endforeach
            </select>
        </div>

        <button data-bs-toggle="modal" data-bs-target="#addStory" class="btn btn-info btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
            </svg>
        </button>
        <button onclick="updateBlock()" class="btn btn-success btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001m-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708z"/>
            </svg>
        </button>
        <button onclick="changePaused()" class="btn btn-primary btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M4.854 14.854a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V3.5A2.5 2.5 0 0 1 6.5 1h8a.5.5 0 0 1 0 1h-8A1.5 1.5 0 0 0 5 3.5v9.793l3.146-3.147a.5.5 0 0 1 .708.708z"/>
            </svg>
        </button>
        <button onclick="deleteBlock()" class="btn btn-danger btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
            </svg>
        </button>
    </div>

    <div class="top-0 end-0 position-fixed pe-2" style="padding-top:70px">
        <div class="dropdown">
            <a class="dropdown-item" data-bs-toggle="dropdown" onclick="focusBlockSearch()">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                </svg>
            </a>
            <ul class="dropdown-menu" id="blocks_search_dropdown" style="max-height: 500px; overflow-y: auto;">
                <input type="text" class="form-control" id="block_search" onkeyup="searchBlocks(this.value)">
                <div id="block_list">
                    @foreach ($blocks as $block)
                        <li id="block_{{$block['id']}}">
                            <a class="dropdown-item search" onclick="highlightSearch({{$block['id']}})">{{$block['title']}}</a>
                        </li>
                    @endforeach
                </div>
            </ul>
        </div>
    </div>

</div>

<div class="modal fade" id="addStory" tabindex="-1" aria-labelledby="addStoryLab" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStoryLab">{{__('labels.newblock')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{__('fields.title')}}</label>
                        <input type="text" class="form-control" id="new_title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{__('fields.descr')}}</label>
                        <textarea type="text" class="form-control" id="new_text" required></textarea>
                    </div>
                <button onclick="addafterBlock()" data-bs-dismiss="modal" class="btn btn-success">{{__('buttons.add')}}</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>

$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Наступна історія (виберіть декілька для розгалуження)",
        allowClear: true
    });
});

let graph = {
    nodes: @json($blocks),
    links: @json($links)
};

let renderedNodes = {};
const container = document.getElementById('graph-container');
let svg = innitSvg();

renderNode({{$start[0]->id ?? 0 }}, window.innerWidth / 2, 50);
resolveOverlaping();
renderUnlinked();
renderConnections();

function innitSvg(){
    let svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    svg.setAttribute('width', '100%');
    svg.setAttribute('height', '100%');
    svg.style.position = 'absolute';
    svg.style.top = '0';
    svg.style.left = '0';
    container.appendChild(svg);

    let defs = document.createElementNS("http://www.w3.org/2000/svg", "defs");

    defs.appendChild(innitMarker());
    svg.appendChild(defs);

    return svg;
}

function innitMarker(){
    let marker = document.createElementNS("http://www.w3.org/2000/svg", "marker");
    marker.setAttribute("id", "arrowhead");
    marker.setAttribute("markerWidth", "10");
    marker.setAttribute("markerHeight", "7");
    marker.setAttribute("refX", "20");
    marker.setAttribute("refY", "3.5");
    marker.setAttribute("orient", "auto");
    marker.setAttribute("markerUnits", "strokeWidth");

    let arrow = document.createElementNS("http://www.w3.org/2000/svg", "polygon");
    arrow.setAttribute("points", "0 0, 10 3.5, 0 7");
    arrow.setAttribute("fill", "black");

    marker.appendChild(arrow);

    return marker;
}

function getChildren(nodeId) {
    return graph.links.filter(link => link.a === nodeId).map(link => link.b);
}

function renderNode(nodeId, x, y, level = 0) {
    const nodeData = graph.nodes.find(node => node.id === nodeId);
    if (!nodeData || renderedNodes[nodeId]) return;
    renderedNodes[nodeId] = true;

    drawNode(nodeData, x, y);

    const children = getChildren(nodeId);
    children.forEach((childId, index) => {
        const childX = x + (index * 150) - (children.length - 1) * 75;
        const childY = y + 150;
        renderNode(childId, childX, childY, level + 1);
    });
}

function drawNode(node, x, y) {
    const nodeElement = document.createElement('div');
    nodeElement.classList.add('node');
    if(node.completed == 1) nodeElement.style.backgroundColor = '#00e397';
    nodeElement.textContent = node.title;
    nodeElement.style.top = `${y}px`;
    nodeElement.style.left = `${x}px`;
    nodeElement.setAttribute('data-id', node.id);

    nodeElement.addEventListener('mousedown', startDrag);
    nodeElement.addEventListener('click', () => selectNode(node.id));

    container.appendChild(nodeElement);
}

function renderConnections() {
    graph.links.forEach(conn => { drawLine(conn.a, conn.b); });
}

function drawLine(from, to) {
    const startNode = document.querySelector(`[data-id="${from}"]`);
    const endNode = document.querySelector(`[data-id="${to}"]`);
    const line = document.createElementNS("http://www.w3.org/2000/svg", "line");
    
    let x1 = startNode.offsetLeft + startNode.offsetWidth / 2;
    let x2 = endNode.offsetLeft + endNode.offsetWidth / 2;
    let y1 = startNode.offsetTop + startNode.offsetHeight / 2;
    let y2 = endNode.offsetTop + endNode.offsetHeight / 2;

    line.setAttribute("x1", x1);
    line.setAttribute("y1", y1);
    line.setAttribute("x2", x2);
    line.setAttribute("y2", y2);
    line.setAttribute("stroke", "black");
    line.setAttribute("stroke-width", "2");
    line.setAttribute('start-node', from);
    line.setAttribute('end-node',   to);

    line.setAttribute("marker-end", "url(#arrowhead)");
    
    svg.appendChild(line);
}

function resolveOverlaping(){
    let nodes = document.querySelectorAll(".node");
    nodes.forEach(node1 => { 
        nodes.forEach(node2 => { 
            if(node1.style.left == node2.style.left 
                && node1.style.top == node2.style.top
                && node1.getAttribute('data-id') != node2.getAttribute('data-id')){
                    node2.style.left = (parseInt(node1.style.left) + 50) + "px";
                    node1.style.left = (parseInt(node1.style.left) - 50) + "px";
                }
        });
    });
}

function renderUnlinked(){
    let x = 100;
    let y = 50;
    let unlinked = graph.nodes.filter(item => !renderedNodes[item.id]);

    unlinked.forEach(node => {
        drawNode(node, x, y);
        y += 150;
    });
}

document.addEventListener('mouseup', endDrag);
document.addEventListener('mousemove', dragging);

let activeBlock = null;
let deltaX = 0;
let deltaY = 0;
function startDrag(event) {
    activeBlock = event.target;
    deltaX = parseInt(activeBlock.style.left) - event.pageX;
    deltaY = parseInt(activeBlock.style.top) - event.pageY;
}

function dragging(event) {
    if (!activeBlock) return;
    activeBlock.style.left = event.pageX + deltaX + "px";
    activeBlock.style.top  = event.pageY + deltaY + "px";
    moveLines(activeBlock);
}

function endDrag() {
    deltaX = 0;
    deltaY = 0;
    activeBlock = null;
}

function moveLines(node) {
    let id = node.getAttribute('data-id');

    let starts = document.querySelectorAll(`[start-node="${id}"]`);
    let ends = document.querySelectorAll(`[end-node="${id}"]`);

    starts.forEach(line => {
        line.setAttribute("x1", node.offsetLeft + node.offsetWidth / 2);
        line.setAttribute("y1", node.offsetTop + node.offsetHeight / 2);
    });

    ends.forEach(line => {
        line.setAttribute("x2", node.offsetLeft + node.offsetWidth / 2);
        line.setAttribute("y2", node.offsetTop + node.offsetHeight / 2);
    });

}

let selectedBlock = null;
function selectNode(id) {
    highlightBlock(id);

    selectedBlock = graph.nodes.find(node => node.id == id);
    document.getElementById('block_title').value = selectedBlock.title;
    document.getElementById('block_text').value  = selectedBlock.text;
    
    let links_to = graph.links.filter(link => link.a == id).map(con => con.b);
    $('#nextNodeSelect').val(links_to).trigger('change');
}

function addafterBlock() {
    let id = selectedBlock?.id ?? 0;
    let title = document.getElementById('new_title');
    let text = document.getElementById('new_text');

    let formData = {
        id_from: id,
        game_id: {{$gameId}},
        title: title.value,
        text: text.value,
        _token: document.querySelector('input[name=_token]').value
    };

    fetch('/addstory', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': formData._token
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.block) {
            let newBlock = data.block;
            graph.nodes.push(newBlock);
            graph.links.push({ a: id, b: newBlock.id });

            let select = $('#nextNodeSelect');
            select.append(new Option(newBlock.title, newBlock.id, false, false)).trigger('change');
            selectNode(id);

            let startnode = document.querySelector(`[data-id="${id}"]`);
            if(startnode){
                let x = parseInt(startnode.style.left);
                let y = parseInt(startnode.style.top) + 150;

                document.querySelectorAll(".node").forEach(node => { 
                if(node.style.left == (x + 'px') && node.style.top == (y + 'px'))
                    x += 50;
                });

                drawNode(newBlock, x, y);
                drawLine(id, newBlock.id);
            } else {
                drawNode(newBlock, window.innerWidth / 2, 50);
            }

            showAlert("{{__('messages.added.story')}}");
        } else
            showAlert("{{__('messages.warning.invalid')}}", 'warning');
    }).catch(error => { console.error('Error:', error);});

    title.value = '';
    text.value = '';
}

function updateBlock() {
    let id = selectedBlock.id;
    let title = document.getElementById('block_title');
    let text = document.getElementById('block_text');

    let selected = $('#nextNodeSelect').val().map(a => a * 1);
    let links_to = graph.links.filter(link => link.a == selectedBlock.id).map(con => con.b);
    let add = selected.filter(val => !links_to.includes(val)).sort();
    let del = links_to.filter(val => !selected.includes(val)).sort();

    let formData = {
        id: id,
        title: title.value,
        text: text.value,
        add_links: add,
        del_links: del,
        _token: document.querySelector('input[name=_token]').value
    };

    fetch('/updatestory', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': formData._token
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            graph.nodes.find(node => node.id == id).title = title.value;
            graph.nodes.find(node => node.id == id).text = text.value;

            document.querySelector(`[data-id="${id}"]`).innerHTML = title.value;

            del.forEach(d => {
                document.querySelector(`line[start-node="${id}"][end-node="${d}"]`).remove();
                graph.links = graph.links.filter(ln => ln.b != d);
            })
            add.forEach(a => {
                graph.links.push({ a: id, b: a });
                drawLine(id, a);
            });

            $(`#nextNodeSelect option[value="${id}"]`).remove();
            $(`#nextNodeSelect`).trigger('change');
            $(`#nextNodeSelect`).append(new Option(title.value, id, false, false)).trigger('change');

            showAlert("{{__('messages.updated.story')}}");
        } else {
            showAlert("{{__('messages.warning.invalid')}}", 'warning');
            title.value = graph.nodes.find(node => node.id == id).title;
            text.value  = graph.nodes.find(node => node.id == id).text;
        }
    }).catch(error => { console.error('Error:', error);});
}

function deleteBlock() {
    if (!confirm("{{__('messages.delete.story')}} ?")) return;
    let id = selectedBlock.id;

    fetch(`/delstory/${id}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            graph.nodes = graph.nodes.filter(node => node.id != id);
            selectedBlock = null;

            $(`#nextNodeSelect option[value="${id}"]`).remove();
            $(`#nextNodeSelect`).trigger('change');
            graph.links = graph.links.filter(link => link.b != id);

            document.querySelector(`.node[data-id="${id}"]`).remove();
            document.querySelectorAll(`[start-node="${id}"]`).forEach(line => line.remove());
            document.querySelectorAll(`[end-node="${id}"]`).forEach(line => line.remove());

            document.getElementById('block_title').value = '';
            document.getElementById('block_text').value = '';

            showAlert("{{__('messages.deleted.story')}}");
        } else 
            showAlert(data.message, 'warning');
    }).catch(error => console.error('Error:', error));
}

function focusBlockSearch() {
    document.getElementById('block_search').focus();
}

function searchBlocks(srch) {
    const filter = srch.toLowerCase();
    const items = document.getElementsByClassName("search");
    for (let i = 0; i < items.length; i++) {
        let txtValue = items[i].textContent || items[i].innerText;
        if (txtValue.toLowerCase().indexOf(filter) > -1)
            items[i].style.display = "";
        else
            items[i].style.display = "none";
    }
}

function highlightBlock(id) {
    document.querySelectorAll('.node').forEach(node => {
        node.classList.remove('border', 'border-5', 'border-warning');
    });
    const block = document.querySelector(`[data-id="${id}"]`);
    block.classList.add('border', 'border-5', 'border-warning');    
    window.scrollTo(parseInt(block.style.left), parseInt(block.style.top) - window.innerHeight / 3);
}

function highlightSearch(id) {
    highlightBlock(id)
    selectNode(id);
}

function changePaused() {
    let id_from = graph.nodes.find(node => node.completed == 1).id;

    let formData = {
        id_from: id_from,
        id_to: selectedBlock.id,
        _token: '{{ csrf_token() }}'
    };

    fetch('/pausestory', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': formData._token
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector(`[data-id="${id_from}"]`).style.backgroundColor = '#4CAF50';
            document.querySelector(`[data-id="${selectedBlock.id}"]`).style.backgroundColor = '#00e397';

            graph.nodes.find(node => node.completed == 1).completed = 0;
            graph.nodes.find(node => node.id == selectedBlock.id).completed = 1;

            showAlert('Звідки починати збереженно');
        } else
            showAlert('Пу пу пу...', 'warning');
    }).catch(error => { console.error('Error:', error);});
}

highlightSearch({{$start[0]->id ?? 0 }})

</script>
@endsection