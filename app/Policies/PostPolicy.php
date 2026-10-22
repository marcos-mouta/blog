<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    // Visualizar: qualquer pessoa, logada ou não (por isso o User é opcional)
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Post $post): bool
    {
        return true;
    }

    // Criar: qualquer usuário logado
    public function create(User $user): bool
    {
        return true;
    }

    // Editar: somente o autor
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    // Apagar: o autor ou um admin
    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id || $user->isAdmin();
    }
}
