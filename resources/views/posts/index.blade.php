@extends('layouts.app')
@section('title', 'MM Blog')
@section('content')
    <div class="page-head">
        <h1>Posts</h1>
        @auth
            <a class="btn btn-icon" href="{{ route('posts.create') }}" title="Novo post" aria-label="Novo post">
                <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 5v14M5 12h14" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"/>
                </svg>
            </a>
        @endauth
    </div>

    @forelse ($posts as $post)
        <article class="entry">
            <h2><a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a></h2>
            <p class="excerpt">{{ \Illuminate\Support\Str::limit($post->body, 220) }}</p>
            <div class="byline">
                <span class="who">
                    <span class="avatar">{{ mb_strtoupper(mb_substr($post->user->name, 0, 1)) }}</span>
                    {{ $post->user->name }}
                </span>
                <time datetime="{{ $post->created_at->toIso8601String() }}">{{ $post->created_at->format('d/m/Y') }}</time>
                <span>{{ $post->comments_count }} {{ $post->comments_count === 1 ? 'comentário' : 'comentários' }}</span>
            </div>
        </article>
    @empty
        <div class="empty">
            <h2>Ainda não há posts</h2>
            @auth
                <p>Escreva o primeiro e comece a conversa.</p>
                <a class="btn" href="{{ route('posts.create') }}">Escrever post</a>
            @else
                <p>Crie uma conta para escrever o primeiro.</p>
                <a class="btn" href="{{ route('register') }}">Criar conta</a>
            @endauth
        </div>
    @endforelse

    @if ($posts->previousPageUrl() || $posts->nextPageUrl())
        <nav class="pager" aria-label="Paginação">
            @if ($posts->previousPageUrl())
                <a class="btn btn-ghost" href="{{ $posts->previousPageUrl() }}">Posts mais recentes</a>
            @else
                <span></span>
            @endif
            @if ($posts->nextPageUrl())
                <a class="btn btn-ghost" href="{{ $posts->nextPageUrl() }}">Posts mais antigos</a>
            @endif
        </nav>
    @endif
@endsection
