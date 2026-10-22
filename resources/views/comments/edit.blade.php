@extends('layouts.app')
@section('title', 'Editar comentário')
@section('content')
    <div class="page-head"><h1>Editar comentário</h1></div>
    <form method="POST" action="{{ route('comments.update', $comment) }}">
        @csrf @method('PUT')
        <div class="field">
            <label for="body">Comentário</label>
            <textarea id="body" name="body" rows="5" required>{{ old('body', $comment->body) }}</textarea>
            @error('body')<div class="error">{{ $message }}</div>@enderror
        </div>
        <div class="form-actions">
            <button type="submit" class="btn">Salvar alterações</button>
            <a class="btn btn-ghost" href="{{ route('posts.show', $comment->post_id) }}">Cancelar</a>
        </div>
    </form>
@endsection
