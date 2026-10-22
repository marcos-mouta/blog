@extends('layouts.app')
@section('title', 'Criar conta')
@section('content')
    <div class="auth-panel">
        <h1>Criar conta</h1>
        <p class="sub">Leva menos de um minuto.</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="field">
                <label for="name">Nome</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
                @error('name')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="password">Senha (mínimo de 8 caracteres)</label>
                <input type="password" id="password" name="password" required>
                @error('password')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="password_confirmation">Repita a senha</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-block">Criar conta</button>
            </div>
        </form>

        <p class="alt">Já tem conta? <a href="{{ route('login') }}">Entrar</a></p>
    </div>
@endsection
