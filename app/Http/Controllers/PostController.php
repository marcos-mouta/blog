<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')->withCount('comments')->latest()->simplePaginate(10);

        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        $post->load(['user', 'comments.user']);

        return view('posts.show', compact('post'));
    }

    public function create()
    {
        Gate::authorize('create', Post::class);

        return view('posts.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Post::class);

        $post = $request->user()->posts()->create($this->validated($request));

        return redirect()->route('posts.show', $post)->with('status', 'Post criado!');
    }

    public function edit(Post $post)
    {
        Gate::authorize('update', $post);

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        Gate::authorize('update', $post);

        $post->update($this->validated($request));

        return redirect()->route('posts.show', $post)->with('status', 'Post atualizado!');
    }

    public function destroy(Post $post)
    {
        Gate::authorize('delete', $post);

        $post->delete();

        return redirect()->route('posts.index')->with('status', 'Post apagado.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);
    }
}
