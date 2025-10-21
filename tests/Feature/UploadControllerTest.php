<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UploadControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_fail_when_invalid_file_is_provided()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/upload', [
            'filename' => 'text',
            'filetype' => 'image/jpeg',
            'filesize' => 3400,
        ]);

        $response->assertStatus(422);
    }

    public function test_success_when_valid_file_is_provided()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $file = UploadedFile::fake()->image('image.jpg');

        $response = $this->postJson('/api/upload', [
            'file' => $file,
        ]);

        $response->assertStatus(201);
    }

    public function test_delete_file()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $file = UploadedFile::fake()->image('image.jpg');

        $uploadedFile = $this->postJson('/api/upload', [
            'file' => $file,
        ]);

        $fileId = $uploadedFile->json('id');

        $response = $this->deleteJson("/api/remove/{$fileId}");

        $response->assertStatus(200);
    }
}
