<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Blog')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600&family=Source+Serif+4:opsz,wght@8..60,400;8..60,600;8..60,700&display=swap" rel="stylesheet">

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
            --serif: 'Source Serif 4', Georgia, 'Times New Roman', serif;
            --sans: 'Public Sans', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
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
        }

        a { color: var(--accent); text-underline-offset: 3px; }
        a:hover { color: var(--accent-dark); }

        :focus-visible { outline: 3px solid #5eead4; outline-offset: 2px; border-radius: 4px; }

        /* ---------- Topo ---------- */
        .topbar { border-bottom: 1px solid var(--line); background: var(--surface); }
        .topbar-inner {
            max-width: 760px; margin: 0 auto; padding: .9rem 1.25rem;
            display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;
        }
        .brand {
            font-family: var(--serif); font-weight: 700; font-size: 1.35rem;
            color: var(--ink); text-decoration: none; letter-spacing: -.01em;
        }
        .brand:hover { color: var(--ink); }
        .topbar .spacer { flex: 1; }
        .topbar .user { display: flex; align-items: center; gap: .5rem; font-size: .9rem; color: var(--muted); }
        .topbar form { margin: 0; }

        /* ---------- Página ---------- */
        .page { max-width: 760px; margin: 0 auto; padding: 2.5rem 1.25rem 4rem; }
        .page-head { display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1.5rem; }
        h1, h2, h3 { font-family: var(--serif); line-height: 1.25; letter-spacing: -.01em; }
        h1 { font-size: 2rem; margin: 0; }
        h2 { font-size: 1.35rem; margin: 0; }

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
        .entry h2 { font-size: 1.5rem; margin-bottom: .4rem; }
        .entry h2 a { color: var(--ink); text-decoration: none; }
        .entry h2 a:hover { color: var(--accent); }
        .excerpt { margin: 0 0 .9rem; color: #374151; font-family: var(--serif); font-size: 1.05rem; line-height: 1.65; }
        .byline { display: flex; align-items: center; flex-wrap: wrap; gap: .4rem 1rem; color: var(--muted); font-size: .85rem; }
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

        .auth-panel { max-width: 420px; margin: 1rem auto 0; }
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

        @media (max-width: 560px) {
            h1, .post-title { font-size: 1.7rem; }
            .page { padding-top: 1.75rem; }
            .page-head { flex-wrap: wrap; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <a class="brand" href="{{ route('posts.index') }}">MM Blog</a>
            <span class="spacer"></span>
            @auth
                <span class="user">
                    <span class="avatar">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                    {{ auth()->user()->name }}
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
    </header>

    <main class="page">
        @if (session('status'))
            <div class="flash" role="status">{{ session('status') }}</div>
        @endif

        @yield('content')
    </main>
</body>
</html>
