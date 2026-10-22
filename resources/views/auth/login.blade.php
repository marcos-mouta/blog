@extends('layouts.app')
@section('title', 'Entrar')
@section('content')
    <div class="auth-panel">
        <h1>Entrar</h1>
        <p class="sub">Acesse sua conta para escrever posts e comentar.</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="field">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" required>
            </div>

            <label class="check"><input type="checkbox" name="remember" value="1"> Manter conectado</label>

            <div class="form-actions">
                <button type="submit" class="btn btn-block">Entrar</button>
            </div>
        </form>

        <p class="alt">Ainda não tem conta? <a href="{{ route('register') }}">Criar conta</a></p>
    </div>
@endsection
