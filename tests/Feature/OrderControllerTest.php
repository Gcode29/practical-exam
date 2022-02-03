<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Products;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderControllerTest extends TestCase
{
    /** @test */
    public function it_can_order_product()
    {
        /** @var \App\Models\User */
        $user = User::factory()->create();

        $product = Products::factory()->create([
            'available_stock' => 100
        ]);

        $payload = [
            'product_id' => $product->id,
            'quantity' => 50
        ];

        $this->actingAs($user)->postJson('/api/order', $payload)
            ->assertStatus(201)
            ->assertJsonFragment(['message' => 'You have successfully ordered this product']);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'available_stock' => 50
        ]);
    }

    /** @test */
    public function it_throws_error_in_quantity()
    {
        /** @var \App\Models\User */
        $user = User::factory()->create();

        $product = Products::factory()->create([
            'available_stock' => 100
        ]);

        $payload = [
            'product_id' => $product->id,
            'quantity' => 101
        ];

        $this->actingAs($user)->postJson('/api/order', $payload)
            ->assertStatus(400)
            ->assertJsonFragment(['message' => 'Failed to order this product due to unavailability of the stock']);

    }
}
