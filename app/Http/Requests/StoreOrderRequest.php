<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true; // Public endpoint
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'customer' => 'required|array',
      'customer.name' => 'required|string|max:255',
      'customer.phone' => 'required|string|max:20',
      'customer.address' => 'nullable|array',
      'customer.address.city' => 'nullable|string|max:255',
      'customer.address.street' => 'nullable|string|max:255',
      'customer.address.building' => 'nullable|string|max:255',
      'items' => 'required|array|min:1',
      'items.*.product_id' => 'required|integer|exists:products,id',
      'items.*.quantity' => 'required|integer|min:1',
      'notes' => 'nullable|string|max:1000',
    ];
  }

  /**
   * Get custom messages for validator errors.
   *
   * @return array<string, string>
   */
  public function messages(): array
  {
    return [
      'customer.required' => 'Customer information is required',
      'customer.name.required' => 'Customer name is required',
      'customer.phone.required' => 'Customer phone is required',
      'items.required' => 'At least one item is required',
      'items.min' => 'At least one item is required',
      'items.*.product_id.required' => 'Product ID is required for each item',
      'items.*.product_id.exists' => 'One or more products do not exist',
      'items.*.quantity.required' => 'Quantity is required for each item',
      'items.*.quantity.min' => 'Quantity must be at least 1',
    ];
  }
}



