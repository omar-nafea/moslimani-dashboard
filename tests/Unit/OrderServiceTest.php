<?php

namespace Tests\Unit;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
  use RefreshDatabase;

  private OrderService $orderService;

  protected function setUp(): void
  {
    parent::setUp();
    $this->orderService = new OrderService();
  }

  public function test_creates_order_with_correct_totals(): void
  {
    $product1 = Product::create([
      'name' => 'Product 1',
      'price' => 50.00,
      'is_active' => true,
    ]);

    $product2 = Product::create([
      'name' => 'Product 2',
      'price' => 100.00,
      'is_active' => true,
    ]);

    $order = $this->orderService->createOrder([
      'customer' => [
        'name' => 'Test',
        'phone' => '123456',
        'address' => [],
      ],
      'items' => [
        ['product_id' => $product1->id, 'quantity' => 2], // 100.00
        ['product_id' => $product2->id, 'quantity' => 1], // 100.00
      ],
    ]);

    $this->assertEquals('200.00', $order->subtotal);
    $this->assertEquals('35.00', $order->shipping_cost);
    $this->assertEquals('235.00', $order->total);
  }

  public function test_generates_unique_invoice_number(): void
  {
    $product = Product::create([
      'name' => 'Product',
      'price' => 10.00,
      'is_active' => true,
    ]);

    $order1 = $this->orderService->createOrder([
      'customer' => ['name' => 'Test', 'phone' => '111', 'address' => []],
      'items' => [['product_id' => $product->id, 'quantity' => 1]],
    ]);

    $order2 = $this->orderService->createOrder([
      'customer' => ['name' => 'Test', 'phone' => '222', 'address' => []],
      'items' => [['product_id' => $product->id, 'quantity' => 1]],
    ]);

    $this->assertNotNull($order1->invoice_number);
    $this->assertNotNull($order2->invoice_number);
    $this->assertNotEquals($order1->invoice_number, $order2->invoice_number);
  }

  public function test_update_status_increments_purchases_on_completion(): void
  {
    $customer = Customer::create([
      'name' => 'Test',
      'phone' => '123',
      'num_purchases' => 0,
    ]);

    $product = Product::create([
      'name' => 'Product',
      'price' => 10.00,
      'is_active' => true,
    ]);

    $order = $this->orderService->createOrder([
      'customer' => ['name' => 'Test', 'phone' => '123', 'address' => []],
      'items' => [['product_id' => $product->id, 'quantity' => 1]],
    ]);

    $this->assertEquals(0, $customer->fresh()->num_purchases);

    $this->orderService->updateStatus($order, Order::STATUS_COMPLETED);

    $this->assertEquals(1, $customer->fresh()->num_purchases);
  }
}



