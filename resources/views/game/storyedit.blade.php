@extends('layouts.layout')

@section('title', 'Історія')

@section('content')
    <div class="container my-5">
        <div id="story-container" class="row justify-content-center text-center">
            
            <div class="col-md-8 mb-5 border rounded history-block">
                <form method="POST" action="/story/{{$game->id}}">
                    @csrf
                    <input type="text" class="my-2" name="title" placeholder="Назва"></input>
                    <textarea class="form-control mb-2" rows="4" name="text"></textarea>

                    <select class="form-control select2" name="next_stories[]" multiple="multiple">
                        @foreach($blocks as $story2)
                            <option value="{{ $story2->id }}">{{ $story2->title }} ({{ $story2->text }})</option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-success mb-2">Записати</button>
                </form>
            </div>

            @foreach($blocks as $story)
                <div data-story-id="{{ $story->id }}" class="col-md-8 mb-5 border rounded history-block">
                    <form id="editform" method="POST" action="/story/{{$story->id}}">
                        @csrf
                        @method('PUT')    
                        <input type="text" class="my-2" name="title" value="{{ $story->title }}"></input>
                        <textarea class="form-control mb-3" rows="4" name="text">{{ $story->text }}</textarea>

                        <select class="form-control select2" name="next_stories[]" multiple="multiple">
                            @foreach($blocks as $story2)
                                @if($story2->id != $story->id)
                                    <option 
                                        @foreach ($story->linkedTo as $linkTo)
                                            {{$linkTo->id==$story2->id?'selected="true"':''}} 
                                        @endforeach
                                    value="{{ $story2->id }}">{{ $story2->title }} ({{ $story2->text }})</option>
                                @endif
                            @endforeach
                        </select>

                        <button type="submit" class="btn btn-warning btn-sm me-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                                <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001m-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708z"/>
                            </svg>
                        </button>
                    </form>
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
            
            <a href="/story/play/{{$game->id}}" class="btn btn-success">Перегляд</a>

        </div>
    </div>
@endsection

@section('scripts')
<script>
    function confirmDeletion(event, title) {
        event.preventDefault();
        if (confirm('Ви точно хочете видалити частинку "' + title + '" ?')) {
            event.target.submit();
        }
    }

    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Наступна історія (виберіть декілька для розгалуження)",
            allowClear: true
        });
    });
</script>
@endsection