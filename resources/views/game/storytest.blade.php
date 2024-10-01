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
    let columns_id = [{{$start->id}}];
    let merge = merger(links);
    let spl = [1,1,1,1,0,0,1,0,1,1,1,1,1];
    let mrg = [0,0,0,0,0,1,1,1,1,1,1,1,1];

    function renderBlocks() {
        let tempLinks = [];
        let ii = 0;

        while (columns_id.length > 0) {
            
            let colId = columns_id.shift();
            // Фільтруємо зв'язки для поточного батьківського блоку
            links = links.filter(link => {
                if (link.a === colId) {
                    tempLinks.push(link);
                    return false;
                }
                return true;
            });

            let parentBlock = document.querySelector(`[data-block-id="${colId}"]`);
            let childContainer = document.createElement('div');
            childContainer.classList.add('child-container', 'mt-2', 'row');

            if(spl[ii] == 1){
                // Якщо є дочірні блоки
                if (tempLinks.length > 0) {
                    tempLinks.forEach(link => {
                        let block = blocks.find(b => b.id === link.b);
                        if (block) {
                            draw_block(block, childContainer);
                            columns_id.push(block.id);  // Додаємо дочірній блок для подальшої обробки
                        }
                    });

                    parentBlock.appendChild(childContainer);  // Виводимо дочірній контейнер під батьківським блоком
                    tempLinks = [];
                }
            }
        
            if(mrg[ii] == 1){
                let mergeBlockId = Object.keys(merge).find(key => merge[key].includes(colId));
                if (mergeBlockId) {
                    let mergeBlock = blocks.find(b => b.id == mergeBlockId);
                    if (mergeBlock) {
                        let closestChildContainer = parentBlock.closest('.child-container'); // Знаходимо найближчий контейнер для злиття
                        if (closestChildContainer) {
                            let mergeContainer = document.createElement('div');
                            mergeContainer.classList.add('child-container', 'mt-2', 'row');
                            
                            draw_block(mergeBlock, mergeContainer);
                            closestChildContainer.appendChild(mergeContainer);  // Виводимо злиття під потрібним контейнером

                            columns_id = columns_id.filter(id => !merge[mergeBlockId].includes(id));
                        }
                    }
                }
            }
        ii++;
        }
    }

    function draw_block(block, container) {
        let blockDiv = document.createElement('div');
        blockDiv.classList.add('card', 'block', 'mb-8', 'col', 'm-1');
        blockDiv.setAttribute('data-block-id', block.id);

        let cardBody = document.createElement('div');
        cardBody.classList.add('card-body');
        let cardTitle = document.createElement('h5');
        cardTitle.classList.add('card-title');
        cardTitle.textContent = block.title;

        let cardText = document.createElement('p');
        cardText.classList.add('card-text');
        cardText.textContent = block.text;

        cardBody.appendChild(cardTitle);
        cardBody.appendChild(cardText);
        blockDiv.appendChild(cardBody);
        container.appendChild(blockDiv);
    }

    function merger(links){
        let merge = {}
        links.forEach(link => {
            if (!merge[link.b]) merge[link.b] = [];
            merge[link.b].push(link.a);
        });

        Object.keys(merge).forEach(mergeKey => {
            if (merge[mergeKey].length == 1) {
                delete merge[mergeKey];
            }
        });

        return merge;
    }

    renderBlocks();
});

</script>
@endsection

