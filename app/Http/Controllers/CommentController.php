<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        Gate::authorize('create', Comment::class);

        $data = $request->validate(['body' => ['required', 'string', 'max:2000']]);

        $comment = new Comment($data);
        $comment->user_id = $request->user()->id;
        $post->comments()->save($comment);

        return redirect()->route('posts.show', $post)->with('status', 'Comentário adicionado!');
    }

    public function edit(Comment $comment)
    {
        Gate::authorize('update', $comment);

        return view('comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        Gate::authorize('update', $comment);

        $comment->update($request->validate(['body' => ['required', 'string', 'max:2000']]));

        return redirect()->route('posts.show', $comment->post_id)->with('status', 'Comentário atualizado!');
    }

    public function destroy(Comment $comment)
    {
        Gate::authorize('delete', $comment);

        $postId = $comment->post_id;
        $comment->delete();

        return redirect()->route('posts.show', $postId)->with('status', 'Comentário apagado.');
    }
}
