<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Blog')</title>

    <style>
        :root {
            --ink: #111827;
            --muted: #6b7280;
            --line: #e5e7eb;
            --canvas: #f6f7f9;
            --surface: #ffffff;
            --accent: #0f766e;
            --accent-dark: #0b5a54;
            --accent-soft: #e6f4f2;
            --danger: #b42318;
            --danger-soft: #fdecea;
            --serif: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            --sans: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;

            /* Tipografia compartilhada: barra de notícias e lista de posts */
            --fs-item-title: .95rem;
            --fs-item-text: .85rem;
            --fs-item-meta: .8rem;

            /* Altura do rodapé fixo */
            --footer-h: 48px;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--surface);
            color: var(--ink);
            font-family: var(--sans);
            font-size: 1rem;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            padding-bottom: var(--footer-h); /* o rodapé fixo não cobre o fim da página */
        }

        a { color: var(--accent); text-underline-offset: 3px; }
        a:hover { color: var(--accent-dark); }

        :focus-visible { outline: 3px solid #5eead4; outline-offset: 2px; border-radius: 4px; }

        /* ---------- Topo ---------- */
        .topbar { border-bottom: 1px solid var(--line); background: var(--surface); }
        .topbar-inner {
            max-width: none; margin: 0; padding: .5rem 2rem;
            display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; gap: 1rem;
        }
        .brand {
            font-family: var(--serif); font-weight: 600; font-size: 1.35rem;
            color: var(--ink); text-decoration: none; letter-spacing: -.01em;
        }
        .brand:hover { color: var(--ink); }
        .brand { display: inline-flex; align-items: center; }
        .brand img { display: block; height: 60px; width: auto; }
        .topbar .spacer { flex: 1; }
        .topbar .user { display: flex; align-items: center; gap: .5rem; font-size: .9rem; color: var(--muted); }
        .topbar form { margin: 0; }
        .topbar-actions { display: flex; align-items: center; justify-content: flex-end; gap: .75rem; }

        /* ---------- Página ---------- */
        .shell { display: grid; grid-template-columns: 230px minmax(0, 1fr) 310px; gap: 0; align-items: start; padding: 0; }
        .page { width: 100%; max-width: calc(720px + 4rem); margin: 0 auto; padding: 2.5rem 2rem 4rem; }
        .page-head { display: flex; align-items: center; justify-content: flex-start; gap: .85rem; margin-bottom: 1.5rem; }
        h1, h2, h3 { font-family: var(--serif); font-weight: 600; line-height: 1.25; letter-spacing: -.01em; }
        h1 { font-size: 2rem; margin: 0; }
        h2 { font-size: 1.35rem; margin: 0; }
        .page-head h1 { font-size: 1.1rem; } /* mesmo tamanho do título "Notícias" */

        /* ---------- Botões ---------- */
        .btn, button.btn {
            display: inline-flex; align-items: center; justify-content: center; gap: .4rem;
            font: 500 .9rem/1 var(--sans); padding: .65rem 1.1rem; border-radius: 8px;
            border: 1px solid var(--accent); background: var(--accent); color: #fff;
            text-decoration: none; cursor: pointer;
        }
        .btn:hover { background: var(--accent-dark); border-color: var(--accent-dark); color: #fff; }
        .btn-ghost { background: transparent; color: var(--ink); border-color: #d1d5db; }
        .btn-ghost:hover { background: var(--canvas); border-color: #9ca3af; color: var(--ink); }
        .btn-danger { background: transparent; color: var(--danger); border-color: #f1c4bf; }
        .btn-danger:hover { background: var(--danger-soft); border-color: var(--danger); color: var(--danger); }
        .btn-small { padding: .45rem .8rem; font-size: .82rem; }
        .btn-block { width: 100%; }
        .btn-icon { width: 1.44rem; height: 1.44rem; padding: 0; border-radius: 50%; flex: none; } /* 40% menor que 2.4rem */
        .btn-icon svg { max-width: 60%; max-height: 60%; }

        /* ---------- Avatar ---------- */
        .avatar {
            width: 1.9rem; height: 1.9rem; border-radius: 50%; flex: none;
            background: var(--accent-soft); color: var(--accent-dark);
            display: inline-flex; align-items: center; justify-content: center;
            font-weight: 600; font-size: .82rem;
        }

        /* ---------- Avisos ---------- */
        .flash {
            border-left: 4px solid var(--accent); background: var(--accent-soft);
            padding: .75rem 1rem; margin-bottom: 1.5rem; border-radius: 0 8px 8px 0; font-size: .95rem;
        }
        .error { color: var(--danger); font-size: .85rem; margin-top: .3rem; }

        /* ---------- Lista de posts ---------- */
        .entry { padding: 1.6rem 0; border-bottom: 1px solid var(--line); }
        .entry:first-of-type { padding-top: .4rem; }
        .entry h2 { font-family: var(--sans); font-size: var(--fs-item-title); font-weight: 600; line-height: 1.35; margin: 0 0 .3rem; }
        .entry h2 a { color: var(--ink); text-decoration: none; }
        .entry h2 a:hover { color: var(--accent); }
        .excerpt { margin: 0 0 .6rem; color: var(--muted); font-family: var(--sans); font-size: var(--fs-item-text); line-height: 1.5; }
        .byline { display: flex; align-items: center; flex-wrap: wrap; gap: .4rem 1rem; color: var(--muted); font-size: var(--fs-item-meta); }
        .byline .who { display: inline-flex; align-items: center; gap: .5rem; color: var(--ink); font-weight: 500; }

        .pager { display: flex; justify-content: space-between; margin-top: 2rem; }

        .empty { text-align: center; padding: 3.5rem 1rem; border: 1px dashed #d1d5db; border-radius: 12px; color: var(--muted); }
        .empty h2 { color: var(--ink); margin-bottom: .4rem; }
        .empty .btn { margin-top: 1rem; }

        /* ---------- Post ---------- */
        .post-title { font-size: 2.4rem; margin-bottom: 1rem; }
        .post-body {
            font-family: var(--serif); font-size: 1.15rem; line-height: 1.8;
            white-space: pre-line; margin: 1.75rem 0; max-width: 68ch;
        }
        .toolbar { display: flex; flex-wrap: wrap; gap: .5rem; align-items: center; padding: 1rem 0; border-top: 1px solid var(--line); }
        .toolbar form { margin: 0; }
        .toolbar .back { margin-left: auto; font-size: .9rem; }

        /* ---------- Comentários ---------- */
        .comments { margin-top: 2.5rem; padding-top: 2rem; border-top: 1px solid var(--line); }
        .comments h2 { margin-bottom: 1.25rem; }
        .comment { display: flex; gap: .85rem; padding: 1rem 0; border-bottom: 1px solid var(--line); }
        .comment:last-of-type { border-bottom: 0; }
        .comment-main { flex: 1; min-width: 0; }
        .comment-meta { display: flex; flex-wrap: wrap; gap: .25rem .75rem; align-items: baseline; font-size: .85rem; color: var(--muted); }
        .comment-meta strong { color: var(--ink); font-weight: 600; }
        .comment-text { margin: .3rem 0 .5rem; white-space: pre-line; overflow-wrap: anywhere; }
        .comment-actions { display: flex; gap: .5rem; align-items: center; }
        .comment-actions form { margin: 0; }
        .link-button { background: none; border: 0; padding: 0; font: inherit; font-size: .85rem; cursor: pointer; color: var(--accent); text-decoration: underline; text-underline-offset: 3px; }
        .link-button.danger { color: var(--danger); }
        .comment-actions a { font-size: .85rem; }

        .comment-form, .login-note { background: var(--canvas); border-radius: 12px; padding: 1.25rem; margin-top: 1.5rem; }
        .login-note { display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
        .login-note p { margin: 0; }

        /* ---------- Formulários ---------- */
        .field { margin-bottom: 1.1rem; }
        label { display: block; font-weight: 500; font-size: .92rem; margin-bottom: .35rem; }
        input[type=text], input[type=email], input[type=password], textarea {
            width: 100%; font: inherit; color: var(--ink); background: var(--surface);
            border: 1px solid #d1d5db; border-radius: 8px; padding: .65rem .8rem;
        }
        textarea { resize: vertical; line-height: 1.6; }
        input:focus, textarea:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(15, 118, 110, .18); }
        .check { display: flex; align-items: center; gap: .5rem; font-weight: 400; }
        .form-actions { display: flex; gap: .6rem; align-items: center; margin-top: 1.5rem; }

        .auth-panel { max-width: 420px; margin: 1rem 0 0; }
        .auth-panel h1 { font-size: 1.8rem; margin-bottom: .4rem; }
        .auth-panel .sub { color: var(--muted); margin: 0 0 1.75rem; }
        .auth-panel .alt { margin-top: 1.5rem; text-align: center; color: var(--muted); font-size: .92rem; }

        /* ---------- Administração ---------- */
        .table-wrap { overflow-x: auto; }
        .table { width: 100%; border-collapse: collapse; font-size: .95rem; }
        .table th { text-align: left; font-weight: 600; color: var(--muted); font-size: .85rem; padding: .6rem .75rem; border-bottom: 1px solid var(--line); }
        .table td { padding: .85rem .75rem; border-bottom: 1px solid var(--line); vertical-align: middle; }
        .badge { display: inline-block; padding: .15rem .6rem; border-radius: 999px; background: var(--accent-soft); color: var(--accent-dark); font-size: .8rem; font-weight: 500; }
        .inline-form { display: flex; gap: .5rem; align-items: center; margin: 0; }
        select { font: inherit; font-size: .9rem; padding: .45rem .6rem; border: 1px solid #d1d5db; border-radius: 8px; background: var(--surface); color: var(--ink); }
        select:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(15, 118, 110, .18); }

        /* ---------- Coluna fixa da esquerda: plataformas ---------- */
        .partners {
            position: sticky; top: 0; margin: 0;
            height: calc(100vh - var(--footer-h)); max-height: calc(100vh - var(--footer-h)); overflow-y: auto;
            background: var(--canvas); border-radius: 0 12px 12px 0; padding: .35rem 1.1rem;
        }
        .partners ul { list-style: none; margin: 0; padding: 0; }
        .partner { padding: 1.15rem 0; border-bottom: 1px solid var(--line); }
        .partner:last-child { border-bottom: 0; }
        .partner img { display: block; height: 48px; width: auto; max-width: 100%; margin-bottom: .6rem; }
        .partner p { margin: 0; font-size: .85rem; line-height: 1.5; color: #374151; }

        /* ---------- Barra lateral de notícias ---------- */
        .news { position: sticky; top: 0; margin: 0; height: calc(100vh - var(--footer-h)); }
        .news-panel { height: 100%; display: flex; flex-direction: column; background: var(--canvas); border-radius: 12px 0 0 12px; padding: 1.1rem 1.1rem 0; overflow: hidden; }
        .news-head { display: flex; align-items: baseline; justify-content: space-between; gap: 1rem; }
        .news-head h2 { font-size: 1.1rem; }
        .news-head a { font-size: .85rem; }
        .news-source { margin: .1rem 0 0; color: var(--muted); font-size: .8rem; }
        .news-viewport {
            flex: 1; min-height: 0; margin-top: .6rem; overflow: hidden;
            -webkit-mask-image: linear-gradient(to bottom, transparent 0, #000 6%, #000 94%, transparent 100%);
            mask-image: linear-gradient(to bottom, transparent 0, #000 6%, #000 94%, transparent 100%);
        }
        .news-track { animation: news-scroll var(--dur, 45s) linear infinite; will-change: transform; }
        .news-viewport:hover .news-track, .news-viewport:focus-within .news-track { animation-play-state: paused; }
        @keyframes news-scroll { from { transform: translateY(0); } to { transform: translateY(-33.3333%); } }
        .news-item { padding: .95rem 0; border-bottom: 1px solid var(--line); }
        .news-item h3 { font-size: var(--fs-item-title); line-height: 1.35; margin: 0 0 .3rem; }
        .news-item p {
            margin: 0 0 .45rem; color: var(--muted); font-size: var(--fs-item-text); line-height: 1.5;
            display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
        }
        .news-item a { font-size: .85rem; }

        /* ---------- Rodapé fixo ---------- */
        .site-footer {
            position: fixed; left: 0; right: 0; bottom: 0; z-index: 50;
            min-height: var(--footer-h);
            display: flex; flex-wrap: wrap; align-items: center; justify-content: center;
            gap: .35rem 1.75rem; padding: .6rem 1rem;
            background: var(--accent-dark); font-size: .85rem;
        }
        .site-footer a { color: #fff; text-decoration: none; }
        .site-footer a:hover { color: #fff; text-decoration: underline; }
        .site-footer a:focus-visible { outline-color: #fff; }

        @media (prefers-reduced-motion: reduce) {
            .news-track { animation: none; }
            .news-viewport { overflow-y: auto; -webkit-mask-image: none; mask-image: none; }
            .news-list[aria-hidden="true"] { display: none; }
        }

        @media (max-width: 1240px) {
            .shell { grid-template-columns: minmax(0, 1fr); gap: 0; padding: 0; }
            .topbar-inner { padding: .5rem 1.25rem; }
            .page { order: 1; padding-left: 1.25rem; padding-right: 1.25rem; }
            .news { order: 2; position: static; height: 420px; margin: 0 0 2rem; }
            .news-panel { border-radius: 12px; }
            .partners { order: 3; position: static; height: auto; max-height: none; margin: 0 0 3rem; border-radius: 12px; }
        }

        @media (max-width: 560px) {
            :root { --footer-h: 76px; }
            .site-footer { gap: .3rem 1.1rem; font-size: .8rem; }
            h1, .post-title { font-size: 1.7rem; }
            .page { padding-top: 1.75rem; }
            .page-head { flex-wrap: wrap; }
            .topbar .user-name { display: none; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <span></span>
            <a class="brand" href="{{ route('posts.index') }}" aria-label="MM Blog - página inicial">
                <img src="{{ asset('images/logo-mm-blog.png') }}" alt="MM Blog">
            </a>
            <div class="topbar-actions">
            @auth
                <span class="user">
                    <span class="avatar">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                    <span class="user-name">{{ auth()->user()->name }}</span>
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-ghost btn-small">Sair</button>
                </form>
            @else
                <a href="{{ route('login') }}">Entrar</a>
                <a class="btn btn-small" href="{{ route('register') }}">Criar conta</a>
            @endauth
            </div>
        </div>
    </header>

    <div class="shell">
    @include('partials.partners-sidebar')
    <main class="page">
        @if (session('status'))
            <div class="flash" role="status">{{ session('status') }}</div>
        @endif

        @yield('content')
    </main>

    <aside class="news" aria-label="Notícias do Grupo Via Máquinas">
        @include('partials.news-sidebar')
    </aside>
    
    </div>

    <footer class="site-footer">
        <nav aria-label="Sites do grupo" style="display: contents;">
            <a href="https://grupoviamaquinas.com.br" target="_blank" rel="noopener">Grupo Via Maquinas</a>
            <a href="https://usadaomaquinas.com.br" target="_blank" rel="noopener">Usadão Máquinas</a>
            <a href="https://viaconsulti.com.br" target="_blank" rel="noopener">Via Consulti</a>
            <a href="https://agranjadigital.com.br" target="_blank" rel="noopener">A Granja Digital</a>
        </nav>
    </footer>
</body>
</html>