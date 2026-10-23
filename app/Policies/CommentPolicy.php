<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    // qualquer usuário logado pode comentar no post
    public function create(User $user): bool
    {
        return true;
    }

    // somente o criador do post pode editar
    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }

    // somente o criado ou o admin pode apagar o post
    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id || $user->isModerator() || $user->isAdmin();
    }
}
