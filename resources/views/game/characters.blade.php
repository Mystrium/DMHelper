@extends('layouts.layout')

@section('title', 'Гравці')

@section('content')
@php $can_edit = !request()->has('play'); @endphp
    <div class="container my-5">
        <a class="btn btn-success position-fixed bottom-0 start-0 m-2" href="/story/{{$gameId}}"><- {{__('links.story')}}</a>

        @if($can_edit)
            <div class="position-fixed end-0 pe-4">
                <div class="dropdown">
                    <a data-bs-toggle="modal" data-bs-target="#attachCharacter">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="green" class="bi bi-plus-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                        </svg>
                    </a>
                </div>
            </div>
        @endif

        <h3 class="my-3">Листи персонажів</h3>
        <div class="row">
            @foreach($characters as $character)
                <div class="col-md-4">
                    <div class="card ps-2 pt-1" onclick="showCharacterList(event, {{ $character['id'] }})" id="character_{{ $character['id'] }}">
                        <h5><span>{{ $character['player'] }}</span> ({{ $character['name'] }})</h5>
                        <p><span>{{ $character['race'] }}</span> {{ $character['class'] }} <span>(lvl: {{ $character['level'] }})</span></p>
                        <p><span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-shield" viewBox="0 0 16 16">
                                <path d="M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533q.18.085.293.118a1 1 0 0 0 .101.025 1 1 0 0 0 .1-.025q.114-.034.294-.118c.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.8 11.8 0 0 1-2.517 2.453 7 7 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7 7 0 0 1-1.048-.625 11.8 11.8 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 63 63 0 0 1 5.072.56"/>
                            </svg>
                        </span> {{ $character['at'] }}</p>
                        <p><span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                                <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/>
                            </svg>
                        </span> {{ $character['hp'] }}/{{ $character['max_hp'] }}</p>

                        <div class="bottom-0 end-0 mb-2">
                            @if($is_owner)
                                <button class="btn btn-warning" onclick='editCharacter(event, @json($character))' data-bs-toggle="modal" data-bs-target="#editCharacterModal" type="submit" class="btn btn-warning btn-sm me-5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                                        <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001m-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708z"/>
                                    </svg>
                                </button>
                            @endif
                            
                            @if($can_edit)
                                <button data-id="{{ $character['id'] }}" onclick="deleteCharacter(this)" class="btn btn-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="modal fade" id="listModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfModalLabel">Інформація про персонажа</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="characterSheetContainer">
                    ...
                </div>
            </div>
        </div>
    </div>

@if($can_edit)
    <div class="modal fade" id="attachCharacter" tabindex="-1" aria-labelledby="createGameLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createGameLabel">{{__('labels.addcharacter')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="/character/{{$gameId}}">
                        @csrf
                        <div class="mb-3">
                            <label for="chId" class="form-label">{{__('fields.apiurl')}}</label>
                            <input type="url" class="form-control" id="chId" name="url" required>
                        </div>
                        <div class="mb-3">
                            <label for="chName" class="form-label">{{__('fields.auth.titles.name')}}</label>
                            <input type="text" class="form-control" id="chName" name="name" required>
                        </div>
                        <button type="submit" class="btn btn-success">{{__('buttons.add')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

@if($is_owner)
    <div class="modal fade" id="editCharacterModal" tabindex="-1" aria-labelledby="editCharacterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCharacterModalLabel">{{ __('Редагувати персонажа') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editCharacterForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="characterId">
                        <div class="mb-3">
                            <label for="characterHp" class="form-label">{{ __('HP') }}</label>
                            <input type="number" class="form-control" id="characterHp" name="hp" required>
                        </div>
                        <div class="mb-3">
                            <label for="characterAt" class="form-label">{{ __('Armor Type') }}</label>
                            <input type="number" class="form-control" id="characterAt" name="at" required>
                        </div>
                        <div class="mb-3">
                            <label for="characterLevel" class="form-label">{{ __('Level') }}</label>
                            <input type="number" class="form-control" id="characterLevel" name="level" required>
                        </div>
                        <div class="mb-3">
                            <label for="characterSpeed" class="form-label">{{ __('Speed') }}</label>
                            <input type="number" class="form-control" id="characterSpeed" name="speed" required>
                        </div>
                        <div class="mb-3">
                            <label for="characterInventory" class="form-label">{{ __('Inventory') }}</label>
                            <textarea class="form-control" id="characterInventory" name="inventory"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="characterNotes" class="form-label">{{ __('Notes') }}</label>
                            <textarea class="form-control" id="characterNotes" name="notes"></textarea>
                        </div>
                        <button type="submit" onclick="updateCharacter(event)" class="btn btn-success" data-bs-dismiss="modal">{{ __('buttons.change') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

@section('scripts')
    <script>
        @if($can_edit)
            function deleteCharacter(btn) {
                event.stopPropagation()
                const charId = btn.getAttribute('data-id');
                const confirmed = confirm("{{__('messages.delete.character')}} ?");

                if (confirmed) {
                    fetch(`/character/${charId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById("character_" + charId).remove();
                            showAlert("{{__('messages.deleted.character')}}");
                        } else showAlert(data.message, 'warning');
                    })
                    .catch(error => console.error('Error:', error));
                }
            };
        @endif

        @if($is_owner)
            function editCharacter(event, character) {
                event.preventDefault();
                event.stopPropagation()
                document.getElementById('characterId').value = character.id;
                document.getElementById('editCharacterModalLabel').innerHTML = '{{ __('Редагувати персонажа') }} "' + character.name + '"';
                document.getElementById('characterHp').value = character.hp;
                document.getElementById('characterAt').value = character.at;
                document.getElementById('characterLevel').value = character.level;
                document.getElementById('characterSpeed').value = character.speed;
                document.getElementById('characterInventory').value = character.inventory;
                document.getElementById('characterNotes').value = character.notes || '';
            }

            function updateCharacter(event) {
                event.preventDefault();

                const formData = new FormData(document.getElementById('editCharacterForm'));
                const characterId = formData.get('id');
                const verificationHash = formData.get('verification_hash');

                fetch(`{{$apiurl}}${characterId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': formData._token
                    },
                    body: JSON.stringify({
                        level: formData.get('level'),
                        hp: formData.get('hp'),
                        at: formData.get('at'),
                        speed: formData.get('speed'),
                        inventory: formData.get('inventory'),
                        notes: formData.get('notes')
                    })
                })
                .then(response => {
                    if (!response.ok)
                        showAlert(response, 'warning');
                    return response.json();
                })
                .then(data => {
                    showAlert("{{__('messages.updated.character')}}");
                    // location.reload();
                })
                .catch(error => {
                    showAlert(error.message, 'warning');
                });
            }
        @endif

        function showCharacterList(event, characterId) {
            const listModal = new bootstrap.Modal(document.getElementById('listModal'));
            listModal.show();

            fetch(`{{$apiurl}}list/${characterId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'text/html'
                }
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('characterSheetContainer').innerHTML = html;
            })
            .catch(error => {
                console.error('Помилка завантаження ліста персонажа:', error);
            });
        }
    </script>
@endsection