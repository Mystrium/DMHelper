@extends('layouts.layout')

@section('title', 'Історія')

@section('content')
    <div class="container my-5">
        <h3 class="pb-3 text-center">Заголовок для Влада</h3>
        <div id="story-container" class="row justify-content-center text-center">
            @foreach($start as $story)
                <div data-story-id="{{ $story->id }}" class="col-md-8 mb-5 border rounded history-block newblock"> 
                    <h5>{{ $story->title }}</h5>
                    <textarea class="form-control mb-3" rows="4">{{ $story->text }}</textarea>
                </div>
            @endforeach

        </div>
        <div style="height:300px"></div>
    </div>
@endsection

@section('scripts')
<script>
    function blockAnimations(){
        document.querySelectorAll('.history-block').forEach(block => {
            block.addEventListener('click', (event) => {
                if (block.classList.contains('completed-block')) { return; }

                document.querySelectorAll('.history-block.active-block').forEach(activeBlock => {
                    activeBlock.classList.remove('active-block');
                    activeBlock.classList.remove('newblock');
                    activeBlock.classList.add('completed-block');
                });

                const parentBlock = event.target.closest('.history-block');
                parentBlock.classList.add('active-block');
                parentBlock.classList.remove('inactive-block');

                window.scrollTo({ top: parentBlock.offsetTop - 250, behavior: 'smooth' });
            });
        });
    }

    blockAnimations();

    $(document).on('click', '.newblock', function() {
        let storyId = $(this).data('story-id');

        $.ajax({
            url: '{{ route('story.next') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                story_id: storyId
            },
            success: function(response) {
                if (response.next_stories) {
                    if(response.next_stories.length > 1) {
                        let newBlock = `<div class="col-md-8 mb-5 border rounded">
                                <ul class="nav nav-tabs" id="storyTabs" role="tablist">`;

                        response.next_stories.forEach(function(story, index) {
                            newBlock += `<li class="nav-item" role="presentation"><button class="nav-link`;
                            if(index == 0) newBlock += ` active`;
                            newBlock += `" id="variant${story.id}-tab" data-bs-toggle="tab" data-bs-target="#variant${story.id}" type="button" role="tab" aria-controls="variant${story.id}" aria-selected="true">${story.title}</button></li>`;
                        });

                        newBlock += `</ul><div class="tab-content mt-3" id="storyTabsContent">`;

                        response.next_stories.forEach(function(story, index) {
                            newBlock += `<div data-story-id="${story.id}" class="history-block inactive-block tab-pane fade newblock`;
                            if(index == 0) newBlock += ` show active`;
                            newBlock += `" id="variant${story.id}" role="tabpanel">
                                    <h5>${story.title}</h5>
                                    <textarea class="form-control mb-3" rows="4">${story.text}</textarea>
                                </div>`;
                        });

                        $('#story-container').append(newBlock + `</div></div>`);
                    } else {
                        let newBlock = `
                            <div data-story-id="${response.next_stories[0].id}" class="col-md-8 mb-5 border rounded history-block newblock">
                                <h5>${response.next_stories[0].title}</h5>
                                <textarea class="form-control mb-3" rows="4">${response.next_stories[0].text}</textarea>
                            </div>`;
                        $('#story-container').append(newBlock);
                    }
                    blockAnimations();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    });

</script>
@endsection
