@extends('layouts.layout')

@section('title', 'Історія')

@section('content')
    <!-- <div id="story-container" class="container"> -->

    <div id="graph-container" style="position: relative; height: 2000px; width: 100%;"></div>

    {{--
        <div class="form-container position-absolute bottom-0 start-50 translate-middle-x text-center">
            <div class="form-group">
                <input type="text" class="form-control" id="block_title" required>
            </div>
            <div class="form-group">
                <textarea class="form-control" id="block_text" rows="3" required></textarea>
            </div>
            <button onclick="updateBlock()" class="btn btn-success">Змінити</button>
            <button type="submit" class="btn btn-danger btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                    <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                </svg>
            </button>
        </div>
    --}}

    <!-- </div> -->
@endsection

@section('scripts')
<script>

let graph = {
    nodes: @json($blocks),
    links: @json($links)
};

let renderedNodes = {};
const container = document.getElementById('graph-container');
let svg = innitSvg();

renderNode({{$start->id}}, window.innerWidth / 2, 50);
resolveOverlaping();
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

    defs.appendChild(marker);

    svg.appendChild(defs);

    return svg;
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
    nodeElement.textContent = `${node.title}`;
    nodeElement.style.top = `${y}px`;
    nodeElement.style.left = `${x}px`;
    nodeElement.setAttribute('data-id', node.id);
    container.appendChild(nodeElement);
}

function renderConnections() {
    graph.links.forEach(conn => {
        const startNode = document.querySelector(`[data-id="${conn.a}"]`);
        const endNode = document.querySelector(`[data-id="${conn.b}"]`);
        if (startNode && endNode) {
            const line = document.createElementNS("http://www.w3.org/2000/svg", "line");
            
            line.setAttribute("x1", startNode.offsetLeft + startNode.offsetWidth / 2);
            line.setAttribute("y1", startNode.offsetTop + startNode.offsetHeight / 2);
            line.setAttribute("x2", endNode.offsetLeft + endNode.offsetWidth / 2);
            line.setAttribute("y2", endNode.offsetTop + endNode.offsetHeight / 2);
            line.setAttribute("stroke", "black");
            line.setAttribute("stroke-width", "2");
            line.setAttribute('start-node', conn.a);
            line.setAttribute('end-node',   conn.b);

            line.setAttribute("marker-end", "url(#arrowhead)");
            
            svg.appendChild(line);
        }
    });
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


document.querySelectorAll(".node").forEach(node => { node.addEventListener('mousedown', startDrag); });
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


function updateBlock() {
    let title = document.getElementById('block_title').value;
    let text = document.getElementById('block_text').value;

    let formData = {
        id: curr_rect,
        title: title,
        text: text,
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
            d3.select("#title_" + curr_rect).text(title);
            d3.select("#text_" + curr_rect).text(text);

            node._groups[0].filter(function(g) {return g.__data__.id == curr_rect})[0].__data__.title = title;
            node._groups[0].filter(function(g) {return g.__data__.id == curr_rect})[0].__data__.text = text;

            showAlert('Частинку оновлено');
        } else 
            showAlert('Поле задовге або пусте', 'warning');
    }).catch(error => { console.error('Error:', error);});

}

</script>
@endsection

<!-- 
    todo update node text
    todo delete node
        with links
        jump link
    todo add node with links
    search nodes by title, text
        zoom-in find node
    todo grab links
        update link
-->