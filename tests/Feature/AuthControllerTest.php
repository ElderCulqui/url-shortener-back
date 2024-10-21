<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_ok()
    {
        // given
        $user = User::factory()->create([
            'email' => 'test@email',
            'password' => bcrypt('password')
        ]);

        // when
        $response = $this->postJson(route('login'), [
            'email' => $user->email,
            'password' => 'password'
        ]);

        // then
        $response->assertOk();
        $response->assertJsonStructure(['id', 'name', 'email', 'token']);
    }

    public function test_login_unauthorized()
    {
        // given
        $user = User::factory()->create([
            'email' => 'test@email',
            'password' => bcrypt('password')
        ]);

        // when
        $response = $this->postJson(route('login'), [
            'email' => $user->email,
            'password' => 'wrong-password'
        ]);

        // then
        $response->assertUnauthorized();
        $response->assertJson(['message' => 'The provided credentials are incorrect.']);
    }
}
