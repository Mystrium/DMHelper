@extends('layouts.layout')

@section('title', 'Вхід')

@section('content')
<div class="container mt-5 w-50">
    <ul class="nav nav-tabs justify-content-center" id="authTab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#login">Вхід</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#register">Реєстрація</button>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
            <form class="mt-4">
                <div class="mb-3">
                    <label for="loginEmail" class="form-label">Пошта</label>
                    <input type="email" class="form-control" id="loginEmail" placeholder="Введіть вашу пошту">
                </div>
                <div class="mb-3">
                    <label for="loginPassword" class="form-label">Пароль</label>
                    <input type="password" class="form-control" id="loginPassword" placeholder="Введіть ваш пароль">
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-success w-50">Увійти</button>
                </div>
            </form>
        </div>

        <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
            <form class="mt-4">
                <div class="mb-3">
                    <label for="registerEmail" class="form-label">Пошта</label>
                    <input type="email" class="form-control" id="registerEmail" placeholder="Введіть вашу пошту">
                </div>
                <div class="mb-3">
                    <label for="registerUsername" class="form-label">Ім'я</label>
                    <input type="text" class="form-control" id="registerUsername" placeholder="Введіть ваш псевдонім">
                </div>
                <div class="mb-3">
                    <label for="registerPassword" class="form-label">Пароль</label>
                    <input type="password" class="form-control" id="registerPassword" placeholder="Введіть ваш пароль">
                </div>
                <div class="mb-3">
                    <label for="registerPassword" class="form-label">Повторіть пароль</label>
                    <input type="password" class="form-control" id="registerPassword" placeholder="Введіть ваш пароль">
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary w-50">Зареєструватись</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection