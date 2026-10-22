<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionsTest extends TestCase
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

    public function test_guest_can_view_posts_and_comments(): void
    {
        $post = $this->makePost(User::factory()->create());
        $this->makeComment($post, User::factory()->create());

        $this->get('/posts')->assertOk();
        $this->get("/posts/{$post->id}")->assertOk()->assertSee('Comentário');
    }

    public function test_guest_cannot_create_post_or_comment(): void
    {
        $post = $this->makePost(User::factory()->create());

        $this->get('/posts/create')->assertRedirect('/login');
        $this->post('/posts', ['title' => 'x', 'body' => 'y'])->assertRedirect('/login');
        $this->post("/posts/{$post->id}/comments", ['body' => 'x'])->assertRedirect('/login');
    }

    public function test_logged_user_can_create_post_and_comment(): void
    {
        $user = User::factory()->create();
        $post = $this->makePost(User::factory()->create());

        $this->actingAs($user)->post('/posts', ['title' => 'Novo', 'body' => 'Texto'])->assertRedirect();
        $this->assertDatabaseHas('posts', ['title' => 'Novo', 'user_id' => $user->id]);

        $this->actingAs($user)->post("/posts/{$post->id}/comments", ['body' => 'Oi'])->assertRedirect();
        $this->assertDatabaseHas('comments', ['body' => 'Oi', 'user_id' => $user->id]);
    }

    public function test_only_author_can_edit_or_delete_post(): void
    {
        $author = User::factory()->create();
        $other = User::factory()->create();
        $post = $this->makePost($author);

        $this->actingAs($other)->put("/posts/{$post->id}", ['title' => 'Hack', 'body' => 'x'])->assertForbidden();
        $this->actingAs($other)->delete("/posts/{$post->id}")->assertForbidden();
        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Título']);

        $this->actingAs($author)->put("/posts/{$post->id}", ['title' => 'Editado', 'body' => 'x'])->assertRedirect();
        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Editado']);

        $this->actingAs($author)->delete("/posts/{$post->id}")->assertRedirect('/posts');
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_only_author_can_edit_or_delete_comment(): void
    {
        $postAuthor = User::factory()->create();
        $commenter = User::factory()->create();
        $post = $this->makePost($postAuthor);
        $comment = $this->makeComment($post, $commenter);

        // Nem o dono do post pode mexer no comentário de outra pessoa
        $this->actingAs($postAuthor)->put("/comments/{$comment->id}", ['body' => 'Hack'])->assertForbidden();
        $this->actingAs($postAuthor)->delete("/comments/{$comment->id}")->assertForbidden();

        $this->actingAs($commenter)->put("/comments/{$comment->id}", ['body' => 'Editado'])->assertRedirect();
        $this->assertDatabaseHas('comments', ['id' => $comment->id, 'body' => 'Editado']);

        $this->actingAs($commenter)->delete("/comments/{$comment->id}")->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}
