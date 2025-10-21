<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_fails_when_invalid_data_is_provided()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/posts', [
            'title' => 'test',
            'content' => '',
        ]);

        $response->assertStatus(422);
    }

    public function test_create_succeeds_when_data_is_provided()
    {
        Sanctum::actingAs(User::factory()->create());
        $response = $this->postJson('/api/posts', [
            'title' => 'test',
            'content' => 'test',
        ]);

        $response->assertStatus(201);
    }

    public function test_update_fails_when_invalid_data_is_provided()
    {
        Sanctum::actingAs(User::factory()->create());
        $post = Post::factory()->create([
            'title' => 'test',
            'content' => 'test',
        ]);

        $response = $this->putJson("/api/posts/{$post->id}", [
            'title' => 'test1',
            'content' => '',
        ]);

        $response->assertStatus(422);
    }

    public function test_update_succeeds_when_data_is_provided()
    {
        Sanctum::actingAs(User::factory()->create());
        $post = Post::factory()->create([
            'title' => 'test',
            'content' => 'test',
        ]);

        $response = $this->putJson("/api/posts/{$post->id}", [
            'title' => 'test1',
            'content' => 'test',
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_function()
    {
        Sanctum::actingAs(User::factory()->create());
        $post = Post::factory()->create();

        $response = $this->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(204);
    }

    public function test_show_function()
    {
        Sanctum::actingAs(User::factory()->create());
        $post = Post::factory()->create();

        $response = $this->getJson("/api/posts/{$post->id}");

        $response->assertStatus(200);
    }
}
