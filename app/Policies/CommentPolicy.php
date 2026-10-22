<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    // Comentar: qualquer usuário logado
    public function create(User $user): bool
    {
        return true;
    }

    // Editar: somente o autor do comentário
    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }

    // Apagar: o autor, um moderador ou um admin
    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id || $user->isModerator() || $user->isAdmin();
    }
}
