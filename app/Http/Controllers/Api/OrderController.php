<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {}

    /**
     * Create a new order.
     * 
     * Expects payload:
     * {
     *   "customer": {
     *     "name": "string",
     *     "phone": "string",
     *     "address": {
     *       "city": "string",
     *       "street": "string",
     *       "building": "string"
     *     }
     *   },
     *   "items": [
     *     { "product_id": 1, "quantity": 2 }
     *   ],
     *   "notes": "optional string"
     * }
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        try {
            $order = $this->orderService->createOrder($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => new OrderResource($order->load(['items', 'customer'])),
            ], 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order. Please try again.',
            ], 500);
        }
    }

    /**
     * Get a single order by ID.
     */
    public function show(int $id): JsonResponse
    {
        $order = Order::with(['items', 'customer'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new OrderResource($order),
        ]);
    }
}
