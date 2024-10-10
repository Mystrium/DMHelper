@extends('layouts.layout')

@section('title', 'Історія')

@section('content')
    <div id="story-container" class="container">

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

    </div>
@endsection

@section('scripts')
<script src="https://d3js.org/d3.v7.min.js"></script>
<script>

const graph = {
    nodes: @json($blocks),
    links: @json($links)
};

var width = window.innerWidth;
var height = window.innerHeight - 60;

const svg = d3.select("body").append("svg").attr("width", width).attr("height", height);

const simulation = d3.forceSimulation(graph.nodes)
    .force("link", d3.forceLink(graph.links).id(d => d.id).distance(150))
    .force("charge", d3.forceManyBody().strength(-500))
    .force("center", d3.forceCenter(width / 2, height / 2));

// Створюємо лінії (зв'язки)
const link = svg.append("g")
    .selectAll("line")
    .data(graph.links)
    .enter()
    .append("line")
    .attr("stroke", "#999")
    .attr("stroke-width", 3);

// Створюємо вузли у вигляді груп
const node = svg.append("g")
    .selectAll("g")
    .data(graph.nodes)
    .enter()
    .append("g")
    .call(d3.drag()
        .on("start", dragstarted)
        .on("drag", dragged)
        .on("end", dragended))
    .on('click', function(d) { handleBlockClick(d) });

// Додаємо прямокутники для карток
node.append("rect")
    .attr("width", d => Math.min(d.text.length, 20) * 9)
    .attr("height", 60)  // Збільшили висоту, щоб вмістити опис
    .attr("rx", 10)  // заокруглені кути
    .attr("ry", 10)
    .attr("id", d => 'rect_' + d.id)
    .attr("fill", "#69b3a2")
    .attr("stroke", "#333")
    .attr("stroke-width", 2);

// Додаємо текст назви всередині карток
node.append("text")
    .attr("x", d => Math.min(d.text.length, 20) * 4.5)  // Центруємо текст
    .attr("y", 20)  // Розміщуємо текст трохи вище по центру
    .attr("id", d => 'title_' + d.id)
    .attr("text-anchor", "middle")
    .attr("alignment-baseline", "middle")
    .attr("fill", "#fff")
    .text(d => d.title);

// Додаємо текст опису під назвою
node.append("text")
    .attr("x", d => Math.min(d.text.length, 20) * 4.5)
    .attr("y", 40)
    .attr("id", d => 'text_' + d.id)
    .attr("text-anchor", "middle")
    .attr("alignment-baseline", "middle")
    .attr("fill", "#fff")
    .text(d => d.text.length > 20 ? d.text.substring(0, 20) + '...' : d.text);

let curr_rect = 0;

function handleBlockClick(block){
    let data = block.target.__data__;
    console.log(data.id + " => " + data.title + " (" + data.text + ")");

    document.getElementById('block_title').value = data.title;
    document.getElementById('block_text').value = data.text;

    d3.select("#rect_" + curr_rect).attr('fill', "#69b3a2");
    d3.select("#rect_" + data.id).attr('fill', "#11915c");
    curr_rect = data.id;
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

// function for d3
simulation.on("tick", () => {
    link.attr("x1", d => d.source.x)
        .attr("y1", d => d.source.y)
        .attr("x2", d => d.target.x)
        .attr("y2", d => d.target.y);

    node.attr("transform", d => `translate(${d.x - 60}, ${d.y - 40})`);
});

function dragstarted(event, d) {
    if (!event.active) simulation.alphaTarget(0.3).restart();
    d.fx = d.x;
    d.fy = d.y;
}

function dragged(event, d) {
    d.fx = event.x;
    d.fy = event.y;
}

function dragended(event, d) {
    if (!event.active) simulation.alphaTarget(0);
    d.fx = null;
    d.fy = null;
}

</script>
@endsection