<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationControllerTest extends TestCase
{
    /** @test */
    public function it_can_register_a_user()
    {
        $payload = [
            'email' => 'test@example.com',
            'password' => '123123'
        ];

        $this->postJson('/api/register', $payload)
            ->assertStatus(201)
            ->assertJsonFragment(['message' => 'User successfully registered']);
    }

    /** @test */
    public function it_throws_an_error_unique_email()
    {
        $user = User::factory()->create();

        $payload = [
            'email' => $user->email,
            'password' => '123123'
        ];

        $this->postJson('/api/register', $payload)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email'])
            ->assertJson([
                'errors' => ['email' => ['Email already taken']]
            ]);
    }
}
