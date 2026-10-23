<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    // qualquer pessoa pode visualizar
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Post $post): bool
    {
        return true;
    }

    // Qualquer um logado pode criar um post
    public function create(User $user): bool
    {
        return true;
    }

    // somente quem criou o post pode editar
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    // somente quem criou o post ou o admin pode apagar
    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id || $user->isAdmin();
    }
}
