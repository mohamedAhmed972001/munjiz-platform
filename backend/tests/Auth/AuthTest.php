<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_as_freelancer()
    {
        Role::create(['name' => 'freelancer']);

        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'freelancer',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_user_can_login()
    {
        Role::create(['name' => 'client']);

        $user = User::factory()->create([
            'password' => bcrypt('secret123'),
        ]);

        $user->assignRole('client');

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        $response->assertStatus(200);
    }
}
