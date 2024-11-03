@extends('layouts.layout')

@section('title', __('links.login'))

@section('content')
<div class="container mt-5 w-50">
    <ul class="nav nav-tabs justify-content-center" id="authTab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#login">{{__('links.login')}}</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#register">{{__('links.signin')}}</button>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
            <form class="mt-4" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="loginEmail" class="form-label">{{__('fields.auth.titles.email')}}</label>
                    <input type="email" name="email" required class="form-control" id="loginEmail" placeholder="{{__('fields.auth.fields.email')}}">
                </div>
                <div class="mb-3">
                    <label for="loginPassword" class="form-label">{{__('fields.auth.titles.password')}}</label>
                    <input type="password" name="password" required class="form-control" id="loginPassword" placeholder="{{__('fields.auth.fields.password')}}">
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-success w-50">{{__('links.login')}}</button>
                </div>
            </form>
        </div>

        <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
            <form class="mt-4" method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-3">
                    <label for="registerEmail" class="form-label">{{__('fields.auth.titles.email')}}</label>
                    <input type="email" name="email" required class="form-control" id="registerEmail" placeholder="{{__('fields.auth.fields.email')}}">
                </div>
                <div class="mb-3">
                    <label for="registerUsername" class="form-label">{{__('fields.auth.titles.name')}}</label>
                    <input type="text" name="name" required class="form-control" id="registerUsername" placeholder="{{__('fields.auth.fields.name')}}">
                </div>
                <div class="mb-3">
                    <label for="registerPassword" class="form-label">{{__('fields.auth.titles.password')}}</label>
                    <input type="password" name="password" required class="form-control" id="registerPassword" placeholder="{{__('fields.auth.fields.password')}}">
                </div>
                <div class="mb-3">
                    <label for="registerPassword" class="form-label">{{__('fields.auth.titles.repeatpass')}}</label>
                    <input type="password" name="password2" required class="form-control" id="registerPassword" placeholder="{{__('fields.auth.fields.password')}}">
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary w-50">{{__('links.signin')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection