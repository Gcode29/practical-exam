<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{

    /** @test */
    public function it_can_authenticate()
    {  
        $user = User::factory()->create();

        $payload = [
            'email' => $user->email,
            'password' => 'password'
        ];

        $this->postJson('/api/login', $payload)
            ->assertStatus(201)
            ->assertJsonStructure(['access_token']);
    }

        /** @test */
        public function it_throws_authentication_error()
        {  
            $user = User::factory()->create();
    
            $payload = [
                'email' => $user->email,
                'password' => '12312312312312312312313'
            ];
    
            $this->postJson('/api/login', $payload)
                ->assertStatus(401)
                ->assertJsonFragment(['message' => 'Invalid credentials']);
        }
}
