@extends('layouts.layout')

@section('title', 'Історія')

@section('content')
    <div class="position-relative">
        <div id="mapContainer" class="border" style="height: 640px; overflow-y: auto; overflow-x: hidden; position: relative;">
            <h3 class="py-3 text-center">{{$title}}</h3>
            <div id="story-container" class="row justify-content-center text-center">
                @foreach($start as $story)
                    <div data-story-id="{{ $story->id }}" class="col-md-8 mb-5 border rounded history-block newblock" onclick="handleBlockClick(this)"> 
                        <h5>{{ $story->title }}</h5>
                        <textarea class="form-control mb-3" rows="4">{{ $story->text }}</textarea>
                    </div>
                @endforeach
            </div>
            <div class="position-fixed bottom-0 end-0 translate-middle-x" style="padding-bottom: 120px">
                <button class="btn btn-warning" onclick="redoStory()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2z"/>
                        <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466"/>
                    </svg>
                </button>
            </div>
            <div class="position-fixed bottom-0 start-0 ps-4" style="padding-bottom: 120px">
                <button class="btn btn-success" onclick="savePaused()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                        <path d="M11 2H9v3h2z"/>
                        <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                    </svg>
                </button>
            </div>
            <div style="height:300px"></div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    let story_start = {{ $story->id }};

    function addBlockClickListener(block) {
        block.setAttribute('onclick', 'handleBlockClick(this)');
    }

    function handleBlockClick(block) {
        block.removeAttribute('onclick');

        document.querySelectorAll('.history-block.active-block').forEach(activeBlock => {
            activeBlock.classList.remove('active-block');
            activeBlock.classList.remove('newblock');
            activeBlock.classList.add('completed-block');
        });

        block.classList.add('active-block');
        block.classList.remove('inactive-block');

        document.getElementById('mapContainer').scrollTo({
            top: block.offsetTop - window.innerHeight / 2 + 160,
            behavior: 'smooth'
        });

        drawNewBlock(block)
    }

    let block_row = 0;
    function drawNewBlock(block) {
        let storyId = $(block).data('story-id');

        $.ajax({
            url: '{{ route('story.next') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                story_id: storyId
            },
            success: function(response) {
                if (response.next_stories) {
                    let newBlock = '';
                    if(response.next_stories.length > 1) {
                        newBlock = `<div class="col-md-8 mb-5 border rounded">
                                <ul class="nav nav-tabs" id="storyTabs" role="tablist">`;

                        response.next_stories.forEach(function(story, index) {
                            newBlock += `<li class="nav-item" role="presentation"><button class="nav-link`;
                            if(index == 0) newBlock += ` active`;
                            newBlock += `" id="variant${story.id}_${block_row}-tab" data-bs-toggle="tab" data-bs-target="#variant${story.id}_${block_row}" type="button" role="tab" aria-controls="variant${story.id}" aria-selected="true">${story.title}</button></li>`;
                        });

                        newBlock += `</ul><div class="tab-content mt-3" id="storyTabsContent">`;

                        response.next_stories.forEach(function(story, index) {
                            newBlock += `<div data-story-id="${story.id}" class="history-block inactive-block tab-pane fade newblock`;
                            if(index == 0) newBlock += ` show active`;
                            newBlock += `" id="variant${story.id}_${block_row}" role="tabpanel">
                                    <h5>${story.title}</h5>
                                    <textarea class="form-control mb-3" rows="4">${story.text}</textarea>
                                </div>`;
                        });

                        newBlock += `</div></div>`;
                    } else {
                        newBlock = `
                            <div data-story-id="${response.next_stories[0].id}" class="col-md-8 mb-5 border rounded history-block newblock">
                                <h5>${response.next_stories[0].title}</h5>
                                <textarea class="form-control mb-3" rows="4">${response.next_stories[0].text}</textarea>
                            </div>`;
                    }
                    $('#story-container').append(newBlock);
                    block_row++;

                    let newBlocks = document.querySelectorAll('.newblock')
                    newBlocks.forEach(newBl => {
                        addBlockClickListener(newBl);
                    });
                } else
                    showAlert(response.message);
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    }

    function redoStory() {
        if(document.querySelectorAll('.history-block').length == 1) return;

        let newBlock = document.querySelector('.newblock:not(.active-block)')
        if(newBlock) newBlock.remove();

        let currBlock = document.querySelector('.newblock.active-block');
        if(currBlock) { 
            currBlock.classList.remove('active-block');
            addBlockClickListener(currBlock);
        }

        let lastCompletedBlock = document.querySelectorAll('.completed-block');
        let lastAddedCompletedBlock = lastCompletedBlock[lastCompletedBlock.length - 1];

        if(lastAddedCompletedBlock){
            lastAddedCompletedBlock.classList.remove('completed-block');
            lastAddedCompletedBlock.classList.add('active-block');
            lastAddedCompletedBlock.classList.add('newblock');

            document.getElementById('mapContainer').scrollTo({
                top: lastAddedCompletedBlock.offsetTop - window.innerHeight / 2 + 160,
                behavior: 'smooth'
            });
        }
    }

    function savePaused() {
        let id_to = document.querySelector('.newblock.active-block').getAttribute('data-story-id');
        console.log(id_to);

        let formData = {
            id_from: story_start,
            id_to: id_to,
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
                showAlert('Звідки починати збереженно');
            } else
                showAlert('Пу пу пу...', 'warning');
        }).catch(error => { console.error('Error:', error);});
    }

    // todo ctrl z for story branching
</script>
@endsection