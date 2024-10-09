@extends('layouts.layout')

@section('title', 'Історія')

@section('content')
    <div id="story-container" class="container">
        {{--<div class="child-container mt-4 row mb-3">
            <div class="card block mb-8 col" data-block-id="{{ $start->id }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $start->title }}</h5>
                    <p class="card-text">{{ $start->text }}</p>
                </div>
            </div>
        </div>--}}
        
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let links = @json($links);
    let blocks = @json($blocks);

    let matr = buildMatrix(links);
    
    console.log(matr);

    matr = transpose(matr);

    levelRepeat(matr);

    drawMatrix(matr);
});


function levelRepeat(matrix) {
    let moved = [];

    for(let i = 0; i < matrix[0].length; i++) {
        for(let j = 0; j < matrix.length - 1; j++){
            if(matrix[j][i] == matrix[j + 1][i]){
                matrix[j][i] = 0;
                moved.push(i);
            }
        }
    }
    console.log(moved);

    const rows = matrix.length;
    const cols = matrix[0].length;

    for (let row = rows - 1; row > 0; row--) {
        let block = [];

        moved.forEach(el => {
            block[el] = matrix[row][el];
            matrix[row][el] = 0;
        });

        let can_move = true;
        block.forEach(el => {
            if(matrix[row].includes(el))
                can_move = false;
        });

        if(can_move){
            moved.forEach(el => {
                matrix[row + 1][el] = block[el];
            });
        } else {
            moved.forEach(el => {
                matrix[row][el] = block[el];
            });
        }
    }
}

function transpose(matrix) {
    return matrix[0].map((col, c) => matrix.map((row, r) => matrix[r][c]));
}

function drawMatrix(matrix) {
    const container = document.createElement('div');
    container.classList.add('container', 'mt-5');

    let contrast = Math.min.apply(null, matrix.flat().filter(Boolean));;

    matrix.forEach(row => {
        // Створюємо div для рядка
        const rowDiv = document.createElement('div');
        rowDiv.classList.add('row', 'justify-content-center'); // Bootstrap row з вирівнюванням по центру та відступом знизу

        // Проходимо по кожному елементу (блоку) в рядку
        row.forEach(block => {
            // Створюємо div для кожного блоку
            const blockDiv = document.createElement('div');
            blockDiv.classList.add('col-auto', 'p-2', 'border', 'text-center'); // Bootstrap колонки і стилі для блоку
            blockDiv.textContent = `[ ${block==0?'.....':block} ]`; // Тут можна виводити текст або id блоку
            blockDiv.style.backgroundColor =`rgb(${240/(block-contrast+1)},${(block-contrast)*16},0)`;

            // Додаємо блок до рядка
            rowDiv.appendChild(blockDiv);
        });

        // Додаємо рядок до контейнера
        container.appendChild(rowDiv);
    });

    document.body.appendChild(container);
}

function buildMatrix(edges) {
    let paths = [];
    let nodeMap = {};

    // Створюємо мапу, де ключ - це вершина "a", а значення - це масив вершин "b"
    edges.forEach(({ a, b }) => {
        if (!nodeMap[a]) {
            nodeMap[a] = [];
        }
        nodeMap[a].push(b);
    });

    // Рекурсивна функція для побудови шляхів
    function traverse(node, path) {
        // Додаємо вузол до шляху
        path.push(node);

        // Якщо немає дочірніх вузлів, шлях завершено
        if (!nodeMap[node]) {
            paths.push([...path]); // Копіюємо поточний шлях і додаємо в список шляхів
            return;
        }

        // Проходимо по всіх дочірніх вузлах
        nodeMap[node].forEach(child => {
            traverse(child, [...path]); // Копіюємо шлях для кожної гілки
        });
    }

    // Починаємо з вузла 1 (початок графа)
    traverse({{ $start->id }}, []);

    // Знаходимо максимальну довжину шляху
    let maxLength = Math.max(...paths.map(path => path.length));

    // Доповнюємо коротші шляхи останнім елементом до досягнення максимальної довжини
    paths = paths.map(path => {
        while (path.length < maxLength) {
            path.push(path[path.length - 1]);
        }
        return path;
    });

    return paths;
}

// const edges = [
    //     { a: 1, b: 2 },
    //     { a: 1, b: 3 },
    //     { a: 2, b: 4 },
    //     { a: 2, b: 5 },
    //     { a: 3, b: 6 },
    //     { a: 3, b: 7 },
    //     { a: 4, b: 8 },
    //     { a: 5, b: 8 },
    //     { a: 6, b: 9 },
    //     { a: 7, b: 9 },
    //     { a: 8, b: 10 },
    //     { a: 9, b: 10 }
    // ];

</script>
@endsection