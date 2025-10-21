<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_to_cart_success()
    {
        Sanctum::actingAs($user = User::factory()->create());
        $product = Product::factory()->create();

        $response = $this->postJson("/api/cart-items/add/{$product->id}", [
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('cart_items', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 3,
        ]);
    }

    public function test_add_to_cart_fail()
    {
        Sanctum::actingAs($user = User::factory()->create());
        $product = Product::factory()->create();

        $response = $this->postJson("/api/cart-items/add/{$product->id}", [
            'quantity' => 3,
        ]);

        $response->assertStatus(422);
    }

    public function test_remove_from_cart_success()
    {
        Sanctum::actingAs($user = User::factory()->create());
        $product = Product::factory()->create();

        CartItem::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        $response = $this->deleteJson("/api/cart-items/remove/{$product->id}/3");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('cart_items', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 3,
        ]);
    }
}
