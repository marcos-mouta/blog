<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Post $post)
    {
        $comment = new Comment($request->validated());
        $comment->user_id = $request->user()->id;
        $post->comments()->save($comment);

        return redirect()->route('posts.show', $post)->with('status', 'Comentário adicionado!');
    }

    public function edit(Comment $comment)
    {
        Gate::authorize('update', $comment);

        return view('comments.edit', compact('comment'));
    }

    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->update($request->validated());

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
