@extends('layouts.layout')

@section('title', 'Історія')

@section('content')
    <div id="story-container" class="container">
        <div id="columns">
            <div class="column" data-column-id="1">
                <div class="block" data-block-id="{{ $start->id }}">
                    {{ $start->title }}
                    {{ $start->text }}
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
        let columns_id = [{{$start->id}}];
        let merge = {};

        links.forEach(link => {
            if (!merge[link.b]) merge[link.b] = [];
            merge[link.b].push(link.a);
        });

        Object.keys(merge).forEach(mergeKey => {
            if(merge[mergeKey].length == 1)
                delete merge[mergeKey];
        });

        console.log('Merge:', merge);

        function renderBlocks() {
            let tempLinks = [];

            // Проходимось по колонкам
            while (columns_id.length > 0) {
                let colId = columns_id.shift();

                console.log('Processing column ID:', colId);

                // Знаходимо всі дочірні елементи
                links = links.filter(link => {
                    if (link.a === colId) {
                        tempLinks.push(link);
                        return false;
                    }
                    return true;
                });

                console.log('Temp Links:', tempLinks);
                console.log('Remaining Links:', links);

                if (tempLinks.length > 0) {
                    let newRow = document.createElement('div');
                    newRow.classList.add('row', 'mt-2');

                    tempLinks.forEach(link => {
                        let block = blocks.find(b => b.id === link.b);
                        if (block) {
                            let colDiv = document.createElement('div');
                            colDiv.classList.add('col-md-6');

                            let blockDiv = document.createElement('div');
                            blockDiv.classList.add('card', 'block', 'mb-8');
                            blockDiv.setAttribute('data-block-id', block.id);

                            let cardBody = document.createElement('div');
                            cardBody.classList.add('card-body');

                            let cardTitle = document.createElement('h5');
                            cardTitle.classList.add('card-title');
                            cardTitle.textContent = block.title;

                            let cardText = document.createElement('p');
                            cardText.classList.add('card-text');
                            cardText.textContent = block.descr;

                            cardBody.appendChild(cardTitle);
                            cardBody.appendChild(cardText);
                            blockDiv.appendChild(cardBody);
                            colDiv.appendChild(blockDiv);

                            // Додаємо колонку з блоком в новий ряд
                            newRow.appendChild(colDiv);

                            // Додаємо ID нового блоку в columns_id для наступних ітерацій
                            columns_id.push(block.id);
                        } else {
                            console.warn('Block not found:', link.b);
                        }
                    });

                    // Додаємо нову колонку в контейнер
                    document.getElementById('columns').appendChild(newRow);

                    // Очищуємо тимчасові зв'язки
                    tempLinks = [];
                }

                // Перевіряємо злиття колонок (merge)
                Object.keys(merge).forEach(mergeKey => {
                    let count = merge[mergeKey].reduce((acc, val) => {
                        return acc + (columns_id.includes(val) ? 1 : 0);
                    }, 0);

                    if (count === merge[mergeKey].length) {
                        // Якщо блоки сходяться, створюємо новий блок
                        let mergedBlock = blocks.find(b => b.id === parseInt(mergeKey));
                        if (mergedBlock) {
                            let newRow = document.createElement('div');
                            newRow.classList.add('row', 'mt-4');

                            let mergedColDiv = document.createElement('div');
                            mergedColDiv.classList.add('col-md-4');

                            let mergedBlockDiv = document.createElement('div');
                            mergedBlockDiv.classList.add('card', 'block', 'mb-4');
                            mergedBlockDiv.setAttribute('data-block-id', mergedBlock.id);

                            let mergedCardBody = document.createElement('div');
                            mergedCardBody.classList.add('card-body');

                            let mergedCardTitle = document.createElement('h5');
                            mergedCardTitle.classList.add('card-title');
                            mergedCardTitle.textContent = mergedBlock.title;

                            let mergedCardText = document.createElement('p');
                            mergedCardText.classList.add('card-text');
                            mergedCardText.textContent = mergedBlock.descr;

                            mergedCardBody.appendChild(mergedCardTitle);
                            mergedCardBody.appendChild(mergedCardText);
                            mergedBlockDiv.appendChild(mergedCardBody);
                            mergedColDiv.appendChild(mergedBlockDiv);

                            newRow.appendChild(mergedColDiv);
                            document.getElementById('columns').appendChild(newRow);

                            // Очищуємо links і merge
                            links = links.filter(link => link.b !== mergeKey);
                            delete merge[mergeKey];
                        } else {
                            console.warn('Merged block not found:', mergeKey);
                        }
                    }
                });
            }
        }

        // Викликаємо функцію для початкового рендеру
        renderBlocks();
    });
</script>
@endsection