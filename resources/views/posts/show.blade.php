@extends('layouts.app')
@section('title', $post->title)
@section('content')
    <article>
        <h1 class="post-title">{{ $post->title }}</h1>

        <div class="byline">
            <span class="who">
                <span class="avatar">{{ mb_strtoupper(mb_substr($post->user->name, 0, 1)) }}</span>
                {{ $post->user->name }}
            </span>
            <time datetime="{{ $post->created_at->toIso8601String() }}">{{ $post->created_at->format('d/m/Y \à\s H:i') }}</time>
        </div>

        <div class="post-body">{{ $post->body }}</div>
    </article>

    <div class="toolbar">
        @can('update', $post)
            <a class="btn btn-ghost btn-small" href="{{ route('posts.edit', $post) }}">Editar post</a>
        @endcan
        @can('delete', $post)
            <form method="POST" action="{{ route('posts.destroy', $post) }}"
                  onsubmit="return confirm('Apagar este post? Os comentários também serão apagados.')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-small">Apagar post</button>
            </form>
        @endcan
        <a class="back" href="{{ route('posts.index') }}">Voltar para os posts</a>
    </div>

    <section class="comments" aria-labelledby="comments-title">
        <h2 id="comments-title">
            {{ $post->comments->count() === 0 ? 'Comentários' : $post->comments->count() . ($post->comments->count() === 1 ? ' comentário' : ' comentários') }}
        </h2>

        @forelse ($post->comments as $comment)
            <div class="comment">
                <span class="avatar">{{ mb_strtoupper(mb_substr($comment->user->name, 0, 1)) }}</span>
                <div class="comment-main">
                    <div class="comment-meta">
                        <strong>{{ $comment->user->name }}</strong>
                        <time datetime="{{ $comment->created_at->toIso8601String() }}">{{ $comment->created_at->format('d/m/Y H:i') }}</time>
                    </div>
                    <p class="comment-text">{{ $comment->body }}</p>
                    <div class="comment-actions">
                        @can('update', $comment)
                            <a href="{{ route('comments.edit', $comment) }}">Editar</a>
                        @endcan
                        @can('delete', $comment)
                            <form method="POST" action="{{ route('comments.destroy', $comment) }}"
                                  onsubmit="return confirm('Apagar este comentário?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="link-button danger">Apagar</button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        @empty
            <p style="color: var(--muted); margin: 0;">Nenhum comentário ainda. Seja a primeira pessoa a comentar.</p>
        @endforelse

        @auth
            <form class="comment-form" method="POST" action="{{ route('comments.store', $post) }}">
                @csrf
                <div class="field" style="margin-bottom: 0;">
                    <label for="body">Escreva um comentário</label>
                    <textarea id="body" name="body" rows="3" required>{{ old('body') }}</textarea>
                    @error('body')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-small">Publicar comentário</button>
                </div>
            </form>
        @else
            <div class="login-note">
                <p>Entre na sua conta para comentar.</p>
                <a class="btn btn-small" href="{{ route('login') }}">Entrar</a>
            </div>
        @endauth
    </section>
@endsection
