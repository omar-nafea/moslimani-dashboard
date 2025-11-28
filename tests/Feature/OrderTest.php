<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
  use RefreshDatabase;

  public function test_can_create_order_with_new_customer(): void
  {
    $product = Product::create([
      'name' => 'Test Product',
      'price' => 100.00,
      'is_active' => true,
    ]);

    $response = $this->postJson('/api/v1/orders', [
      'customer' => [
        'name' => 'Test Customer',
        'phone' => '+201234567890',
        'address' => [
          'city' => 'Cairo',
          'street' => 'Test Street',
          'building' => '123',
        ],
      ],
      'items' => [
        ['product_id' => $product->id, 'quantity' => 2],
      ],
      'notes' => 'Test order',
    ]);

    $response->assertStatus(201)
      ->assertJson([
        'success' => true,
      ]);

    $this->assertDatabaseHas('customers', [
      'phone' => '+201234567890',
      'name' => 'Test Customer',
    ]);

    $this->assertDatabaseHas('orders', [
      'phone' => '+201234567890',
      'subtotal' => 200.00,
    ]);
  }

  public function test_updates_existing_customer_on_order_creation(): void
  {
    $customer = Customer::create([
      'name' => 'Old Name',
      'phone' => '+201234567890',
      'address_city' => 'Old City',
      'num_purchases' => 0,
    ]);

    $product = Product::create([
      'name' => 'Test Product',
      'price' => 100.00,
      'is_active' => true,
    ]);

    $response = $this->postJson('/api/v1/orders', [
      'customer' => [
        'name' => 'New Name',
        'phone' => '+201234567890',
        'address' => [
          'city' => 'New City',
        ],
      ],
      'items' => [
        ['product_id' => $product->id, 'quantity' => 1],
      ],
    ]);

    $response->assertStatus(201);

    $customer->refresh();
    $this->assertEquals('New Name', $customer->name);
    $this->assertEquals('New City', $customer->address_city);
  }

  public function test_jwt_login_returns_token(): void
  {
    $user = User::factory()->admin()->create([
      'email' => 'test@example.com',
      'password' => bcrypt('password'),
    ]);

    $response = $this->postJson('/api/v1/auth/login', [
      'email' => 'test@example.com',
      'password' => 'password',
    ]);

    $response->assertStatus(200)
      ->assertJsonStructure([
        'success',
        'access_token',
        'token_type',
        'expires_in',
        'user',
      ]);
  }

  public function test_invoice_download_requires_valid_order(): void
  {
    $response = $this->get('/api/v1/orders/invoice/INVALID-123');

    $response->assertStatus(404);
  }
}



