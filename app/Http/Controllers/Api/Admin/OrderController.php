<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
  public function __construct(
    private OrderService $orderService
  ) {
  }

  /**
   * List all orders with optional filtering.
   */
  public function index(Request $request): JsonResponse
  {
    $query = Order::with(['customer', 'items']);

    // Filter by status
    if ($request->has('status')) {
      $query->status($request->status);
    }

    $orders = $query->orderBy('created_at', 'desc')
      ->paginate($request->get('per_page', 15));

    return response()->json([
      'success' => true,
      'data' => $orders->map(fn($order) => [
        'id' => $order->id,
        'invoice_number' => $order->invoice_number,
        'status' => $order->status,
        'total' => $order->total,
        'phone' => $order->phone,
        'customer' => [
          'id' => $order->customer->id,
          'name' => $order->customer->name,
        ],
        'items_count' => $order->items->count(),
        'created_at' => $order->created_at->toISOString(),
      ]),
      'meta' => [
        'current_page' => $orders->currentPage(),
        'last_page' => $orders->lastPage(),
        'per_page' => $orders->perPage(),
        'total' => $orders->total(),
      ],
    ]);
  }

  /**
   * Update order status.
   */
  public function updateStatus(Request $request, int $id): JsonResponse
  {
    $request->validate([
      'status' => 'required|in:pending,completed,canceled',
    ]);

    $order = Order::findOrFail($id);

    try {
      $order = $this->orderService->updateStatus($order, $request->status);

      return response()->json([
        'success' => true,
        'message' => 'Order status updated successfully',
        'order' => [
          'id' => $order->id,
          'status' => $order->status,
        ],
      ]);
    } catch (\InvalidArgumentException $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
      ], 400);
    }
  }
}



