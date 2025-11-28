<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderService
{
  /**
   * Default shipping cost.
   */
  private const DEFAULT_SHIPPING_COST = '35.00';

  /**
   * Create a new order from the given data.
   *
   * @param array $data Order data including customer info and items
   * @return Order The created order with items loaded
   */
  public function createOrder(array $data): Order
  {
    return DB::transaction(function () use ($data) {
      // Find or create customer by phone
      $customer = $this->findOrCreateCustomer($data['customer']);

      // Calculate totals and validate products
      $items = $this->prepareOrderItems($data['items']);
      $subtotal = $this->calculateSubtotal($items);
      $shippingCost = self::DEFAULT_SHIPPING_COST;
      $total = number_format((float) $subtotal + (float) $shippingCost, 2, '.', '');

      // Create the order
      $order = Order::create([
        'customer_id' => $customer->id,
        'status' => Order::STATUS_PENDING,
        'subtotal' => $subtotal,
        'shipping_cost' => $shippingCost,
        'total' => $total,
        'phone' => $data['customer']['phone'],
        'address_street' => $data['customer']['address']['street'] ?? null,
        'address_building' => $data['customer']['address']['building'] ?? null,
        'address_city' => $data['customer']['address']['city'] ?? null,
        'notes' => $data['notes'] ?? null,
      ]);

      // Generate invoice number
      $order->invoice_number = Order::generateInvoiceNumber($order->id);
      $order->save();

      // Create order items
      foreach ($items as $item) {
        OrderItem::create([
          'order_id' => $order->id,
          'product_id' => $item['product']->id,
          'name' => $item['product']->name,
          'price' => $item['product']->price,
          'quantity' => $item['quantity'],
          'total' => $item['total'],
        ]);
      }

      return $order->load('items', 'customer');
    });
  }

  /**
   * Find an existing customer by phone or create a new one.
   * If found, update the address fields.
   */
  private function findOrCreateCustomer(array $customerData): Customer
  {
    $customer = Customer::where('phone', $customerData['phone'])->first();

    if ($customer) {
      // Update existing customer
      $customer->update([
        'name' => $customerData['name'],
        'address_city' => $customerData['address']['city'] ?? $customer->address_city,
        'address_street' => $customerData['address']['street'] ?? $customer->address_street,
        'address_building' => $customerData['address']['building'] ?? $customer->address_building,
      ]);
    } else {
      // Create new customer
      $customer = Customer::create([
        'name' => $customerData['name'],
        'phone' => $customerData['phone'],
        'address_city' => $customerData['address']['city'] ?? null,
        'address_street' => $customerData['address']['street'] ?? null,
        'address_building' => $customerData['address']['building'] ?? null,
        'num_purchases' => 0,
      ]);
    }

    return $customer;
  }

  /**
   * Prepare order items by fetching products and calculating totals.
   *
   * @param array $itemsData Items with product_id and quantity
   * @return array Prepared items with product models and calculated totals
   */
  private function prepareOrderItems(array $itemsData): array
  {
    $items = [];

    foreach ($itemsData as $itemData) {
      $product = Product::findOrFail($itemData['product_id']);

      if (!$product->is_active) {
        throw new \InvalidArgumentException("Product '{$product->name}' is not available.");
      }

      $total = OrderItem::calculateTotal($product->price, $itemData['quantity']);

      $items[] = [
        'product' => $product,
        'quantity' => $itemData['quantity'],
        'total' => $total,
      ];
    }

    return $items;
  }

  /**
   * Calculate the subtotal from prepared items.
   */
  private function calculateSubtotal(array $items): string
  {
    $subtotal = 0.0;

    foreach ($items as $item) {
      $subtotal += (float) $item['total'];
    }

    return number_format($subtotal, 2, '.', '');
  }

  /**
   * Update order status.
   */
  public function updateStatus(Order $order, string $status): Order
  {
    if (!in_array($status, Order::STATUSES)) {
      throw new \InvalidArgumentException("Invalid status: {$status}");
    }

    $previousStatus = $order->status;

    $order->status = $status;
    $order->save();

    // Increment customer purchases when order is completed
    if ($status === Order::STATUS_COMPLETED && $previousStatus !== Order::STATUS_COMPLETED) {
      $order->customer->incrementPurchases();
    }

    return $order;
  }
}



