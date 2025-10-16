<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_fails_when_invalid_data_is_provided()
    {
        $response = $this->postJson('/api/register', [
            'email'=>'Test',
            'name'=>'Test',
            'password'=>'password',
            'password_confirmation'=>'password1'
        ]);

        $response->assertStatus(422);
    }

    public function test_register_passes_when_valid_data_is_provided()
    {
        $response = $this->postJson('/api/register', [
            'email'=>'Test@example.com',
            'name'=>'Test',
            'password'=>'password',
            'password_confirmation'=>'password'
        ]);

        $response->assertStatus(200);
    }

    public function test_login_fails_when_invalid_data_is_provided()
    {
        $response = $this->postJson('/api/login', [
            'email'=>'test@example.com',
            'password'=>'passw'
        ]);

        $response->assertStatus(422);
    }

    public function test_login_passes_when_valid_data_is_provided()
    {
        $user = User::factory()->create([
            'email'=>'test@example.com',
            'password'=>Hash::make('password')
        ]);
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/login', [
            'email'=>'test@example.com',
            'password'=>'password'
        ]);

        $response->assertStatus(200);
    }

    public function test_password_change_fails_when_invalid_data_is_provided()
    {
        $user = User::factory()->create([
            'email'=>'test@example.com',
            'password'=>Hash::make('password')
        ]);

        Sanctum::actingAs($user);

        $response = $this->putJson('/api/change-password', [
            'current_password'=>'password',
            'new_password'=>'password1',
            'password_confirmation'=>'password'
        ]);

        $response->assertStatus(422);
    }

    public function test_password_change_passes_when_valid_data_is_provided()
    {
        $user = User::factory()->create([
            'email'=>'test@example.com',
            'password'=>Hash::make('password')
        ]);

        Sanctum::actingAs($user);
        $response = $this->putJson('/api/change-password', [
            'current_password'=>'password',
            'new_password'=>'password1',
            'new_password_confirmation'=>'password1'
        ]);

        $response->assertStatus(200);
    }
}
