@extends('layouts.layout')

@section('title', 'Учасники')

@section('content')
<div class="container my-3">
    <h2>Учасники</h2>
    @foreach ($users as $user)
        <div class="list-group pt-2">
            <a href="/profile/{{ $user->id }}" class="list-group-item" aria-current="true">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{{ $user->name }}</h5>
                    <small>{{ $user->id }}</small>
                </div>
                <p class="mb-1">{{ $user->email }}</p>
            </a>
        </div>
    @endforeach
</div>
@endsection
@section('scripts')
<script>


</script>