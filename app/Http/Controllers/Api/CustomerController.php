<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Create a new customer (or return existing if phone matches).
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_city' => 'nullable|string|max:255',
            'address_street' => 'nullable|string|max:255',
            'address_building' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Check if customer exists by phone
        $customer = Customer::where('phone', $request->phone)->first();

        if ($customer) {
            // Update existing customer
            $customer->update([
                'name' => $request->name,
                'address_city' => $request->address_city ?? $customer->address_city,
                'address_street' => $request->address_street ?? $customer->address_street,
                'address_building' => $request->address_building ?? $customer->address_building,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully',
                'data' => new CustomerResource($customer),
                'is_new' => false,
            ]);
        }

        // Create new customer
        $customer = Customer::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address_city' => $request->address_city,
            'address_street' => $request->address_street,
            'address_building' => $request->address_building,
            'num_purchases' => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully',
            'data' => new CustomerResource($customer),
            'is_new' => true,
        ], 201);
    }

    /**
     * Update an existing customer.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:20|unique:customers,phone,' . $id,
            'address_city' => 'nullable|string|max:255',
            'address_street' => 'nullable|string|max:255',
            'address_building' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $customer->update($request->only([
            'name',
            'phone',
            'address_city',
            'address_street',
            'address_building',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully',
            'data' => new CustomerResource($customer),
        ]);
    }
}

