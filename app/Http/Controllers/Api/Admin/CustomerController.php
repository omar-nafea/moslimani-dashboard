<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
  /**
   * List all customers.
   */
  public function index(Request $request): JsonResponse
  {
    $query = Customer::withCount('orders');

    // Search by name or phone
    if ($request->has('q') && $request->q) {
      $search = $request->q;
      $query->where(function ($q) use ($search) {
        $q->where('name', 'ilike', "%{$search}%")
          ->orWhere('phone', 'ilike', "%{$search}%");
      });
    }

    $customers = $query->orderBy('created_at', 'desc')
      ->paginate($request->get('per_page', 15));

    return response()->json([
      'success' => true,
      'data' => $customers->map(fn($customer) => [
        'id' => $customer->id,
        'name' => $customer->name,
        'phone' => $customer->phone,
        'address' => [
          'city' => $customer->address_city,
          'street' => $customer->address_street,
          'building' => $customer->address_building,
        ],
        'num_purchases' => $customer->num_purchases,
        'orders_count' => $customer->orders_count,
        'created_at' => $customer->created_at->toISOString(),
      ]),
      'meta' => [
        'current_page' => $customers->currentPage(),
        'last_page' => $customers->lastPage(),
        'per_page' => $customers->perPage(),
        'total' => $customers->total(),
      ],
    ]);
  }

  /**
   * Get a single customer with their orders.
   */
  public function show(int $id): JsonResponse
  {
    $customer = Customer::with([
      'orders' => function ($q) {
        $q->orderBy('created_at', 'desc')->limit(10);
      }
    ])->findOrFail($id);

    return response()->json([
      'success' => true,
      'data' => [
        'id' => $customer->id,
        'name' => $customer->name,
        'phone' => $customer->phone,
        'address' => [
          'city' => $customer->address_city,
          'street' => $customer->address_street,
          'building' => $customer->address_building,
        ],
        'num_purchases' => $customer->num_purchases,
        'recent_orders' => $customer->orders->map(fn($order) => [
          'id' => $order->id,
          'invoice_number' => $order->invoice_number,
          'status' => $order->status,
          'total' => $order->total,
          'created_at' => $order->created_at->toISOString(),
        ]),
        'created_at' => $customer->created_at->toISOString(),
      ],
    ]);
  }
}



