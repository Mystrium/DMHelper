@extends('layouts.layout')

@section('title', 'Історія')

@section('content')
    <div id="story-container" class="container">
        <div class="child-container mt-4 row mb-3">
            <div class="card block mb-8 col" data-block-id="{{ $start->id }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $start->title }}</h5>
                    <p class="card-text">{{ $start->text }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let links = @json($links);
    let blocks = @json($blocks);
    
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
        traverse(1, []);

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

    // Приклад даних
    const edges = [
        { a: 1, b: 2 },
        { a: 1, b: 3 },
        { a: 2, b: 4 },
        { a: 2, b: 5 },
        { a: 3, b: 6 },
        { a: 3, b: 7 },
        { a: 4, b: 8 },
        { a: 5, b: 8 },
        { a: 6, b: 9 },
        { a: 7, b: 9 },
        { a: 8, b: 10 },
        { a: 9, b: 10 }
    ];

    // Виклик функції та виведення результату
    console.log(buildMatrix(edges));
});
</script>
@endsection

