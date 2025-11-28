<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ComingProductResource;
use App\Models\ComingProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ComingProductController extends Controller
{
    /**
     * List all coming products.
     */
    public function index(Request $request): JsonResponse
    {
        $query = ComingProduct::query();

        // Filter by active status (default to active only)
        if ($request->has('active')) {
            $query->where('is_active', filter_var($request->active, FILTER_VALIDATE_BOOLEAN));
        } else {
            $query->where('is_active', true);
        }

        $products = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => ComingProductResource::collection($products),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ]);
    }
}
