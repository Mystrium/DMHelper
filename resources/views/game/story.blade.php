@extends('layouts.layout')

@section('title', 'Мапа світу')

@section('content')
    <div class="container my-5">
        <div class="row justify-content-center text-center">
            @foreach($blocks as $story)
                <div class="col-md-8 mb-5 border rounded history-block {{$loop->first?'active-block':''}}">
                    <h5>{{ $story->title }}</h5> 
                    <textarea class="form-control mb-3" rows="4">{{ $story->text }}</textarea>

                    <form method="POST" action="{{ route('story.destroy', ['id' => $story->id]) }}" class="mb-2" onsubmit="return confirmDeletion(event, '{{$story->title}}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm me-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                            </svg>
                        </button>
                    </form>

                </div>
            @endforeach
            
            <div class="col-md-8 mb-5 border rounded history-block">
                <ul class="nav nav-tabs" id="storyTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="variant1-tab" data-bs-toggle="tab" data-bs-target="#variant1" type="button" role="tab" aria-controls="variant1" aria-selected="true">Варіант 1</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="variant2-tab" data-bs-toggle="tab" data-bs-target="#variant2" type="button" role="tab" aria-controls="variant2" aria-selected="false">Варіант 2</button>
                    </li>
                </ul>

                <div class="tab-content mt-3" id="storyTabsContent">
                    
                    <div class="tab-pane fade show active" id="variant1" role="tabpanel" aria-labelledby="variant1-tab">
                        <div class="mb-5">
                            <h5>cccccccccccccccccccccccc</h5>
                            <textarea class="form-control mb-3" rows="4">Цей текст також можна редагувати. Це третій блок історії.</textarea>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="variant2" role="tabpanel" aria-labelledby="variant2-tab">
                        <div class="mb-5">
                            <h5>cccccccccccccccccccccccc</h5>
                            <textarea class="form-control mb-3" rows="4">Цей текст також можна редагувати. Це третій блок історії.</textarea>
                        </div>
                    </div>

                </div>
            </div>


            <div class="col-md-8 mb-5 border rounded history-block">
                <form method="POST" action="/story/{{$game->id}}">
                    @csrf
                    <input type="text" class="my-2" name="title"></input>
                    <textarea class="form-control mb-2" rows="4" name="text"></textarea>

                    <select class="form-select mb-2" name="next_story">
                        @foreach($blocks as $story)
                            <option value="{{ $story->id }}">{{ $story->title }} ({{ $story->text }})</option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-success mb-2">Записати</button>
                </form>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.history-block ').forEach(textarea => {
        textarea.addEventListener('click', (event) => {
            document.querySelectorAll('.history-block').forEach(block => {
                block.classList.remove('active-block');
                block.classList.add('inactive-block');
            });

            const parentBlock = event.target.closest('.history-block');
            parentBlock.classList.add('active-block');
            parentBlock.classList.remove('inactive-block');

            window.scrollTo({ top: parentBlock.offsetTop - 250, behavior: 'smooth'});
        });
    });

    function confirmDeletion(event, title) {
        event.preventDefault();
        if (confirm('Ви точно хочете видалити частинку "' + title + '" ?')) {
            event.target.submit();
        }
    }
</script>
@endsection