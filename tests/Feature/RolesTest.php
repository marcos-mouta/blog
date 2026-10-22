<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolesTest extends TestCase
{
    use RefreshDatabase;

    private function makePost(User $author): Post
    {
        $post = new Post(['title' => 'Título', 'body' => 'Conteúdo']);
        $post->user_id = $author->id;
        $post->save();

        return $post;
    }

    private function makeComment(Post $post, User $author): Comment
    {
        $comment = new Comment(['body' => 'Comentário']);
        $comment->user_id = $author->id;
        $post->comments()->save($comment);

        return $comment;
    }

    public function test_new_users_get_the_user_role_and_cannot_choose_their_role(): void
    {
        $this->post('/register', [
            'name' => 'Teste',
            'email' => 'teste@example.com',
            'password' => 'senha12345',
            'password_confirmation' => 'senha12345',
            'role' => 'admin',
        ])->assertRedirect();

        $this->assertDatabaseHas('users', ['email' => 'teste@example.com', 'role' => 'user']);
    }

    public function test_moderator_can_delete_any_comment_but_not_edit_it_or_delete_posts(): void
    {
        $moderator = User::factory()->create(['role' => 'moderator']);
        $author = User::factory()->create();
        $post = $this->makePost($author);
        $comment = $this->makeComment($post, $author);

        $this->actingAs($moderator)->put("/comments/{$comment->id}", ['body' => 'Hack'])->assertForbidden();
        $this->actingAs($moderator)->delete("/posts/{$post->id}")->assertForbidden();

        $this->actingAs($moderator)->delete("/comments/{$comment->id}")->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }

    public function test_admin_can_delete_any_post_and_comment_but_not_edit_them(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $author = User::factory()->create();
        $post = $this->makePost($author);
        $comment = $this->makeComment($post, $author);

        $this->actingAs($admin)->put("/posts/{$post->id}", ['title' => 'Hack', 'body' => 'x'])->assertForbidden();
        $this->actingAs($admin)->put("/comments/{$comment->id}", ['body' => 'Hack'])->assertForbidden();

        $this->actingAs($admin)->delete("/comments/{$comment->id}")->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);

        $this->actingAs($admin)->delete("/posts/{$post->id}")->assertRedirect('/posts');
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_regular_user_still_cannot_delete_other_peoples_content(): void
    {
        $user = User::factory()->create();
        $author = User::factory()->create();
        $post = $this->makePost($author);
        $comment = $this->makeComment($post, $author);

        $this->actingAs($user)->delete("/comments/{$comment->id}")->assertForbidden();
        $this->actingAs($user)->delete("/posts/{$post->id}")->assertForbidden();
    }
}
