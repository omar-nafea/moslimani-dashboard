<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ComingProductController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Api\Admin\OrderController as AdminOrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ==========================================================================
// HEALTH CHECK (For Render.com)
// ==========================================================================

Route::get('health', function () {
  return response()->json([
    'status' => 'ok',
    'timestamp' => now()->toISOString(),
    'app' => config('app.name'),
    'version' => '1.0.0',
  ]);
});

// ==========================================================================
// PUBLIC ROUTES (No Authentication Required)
// ==========================================================================

// Products
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{id}', [ProductController::class, 'show']);

// Coming Products
Route::get('coming-products', [ComingProductController::class, 'index']);

// Customers (auto-create/update from order or direct)
Route::post('customers', [CustomerController::class, 'store']);
Route::patch('customers/{id}', [CustomerController::class, 'update']);

// Orders
Route::post('orders', [OrderController::class, 'store']);
Route::get('orders/{id}', [OrderController::class, 'show']);

// Public invoice download by invoice number
Route::get('orders/invoice/{invoiceNumber}', [InvoiceController::class, 'downloadByInvoiceNumber'])
  ->name('api.orders.invoice.public');

// ==========================================================================
// AUTHENTICATION ROUTES
// ==========================================================================

Route::prefix('auth')->group(function () {
  Route::post('login', [AuthController::class, 'login']);

  Route::middleware('jwt.auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
  });
});

// ==========================================================================
// ADMIN ROUTES (JWT Authentication Required)
// ==========================================================================

Route::prefix('admin')->middleware('jwt.auth')->group(function () {
  // Orders Management
  Route::get('orders', [AdminOrderController::class, 'index']);
  Route::patch('orders/{id}/status', [AdminOrderController::class, 'updateStatus']);
  Route::get('orders/{id}/invoice', [InvoiceController::class, 'download'])
    ->name('api.admin.orders.invoice');

  // Customers Management
  Route::get('customers', [AdminCustomerController::class, 'index']);
  Route::get('customers/{id}', [AdminCustomerController::class, 'show']);
});

// ==========================================================================
// LEGACY V1 ROUTES (for backward compatibility)
// ==========================================================================

Route::prefix('v1')->group(function () {
  Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('jwt.auth')->group(function () {
      Route::post('logout', [AuthController::class, 'logout']);
      Route::post('refresh', [AuthController::class, 'refresh']);
      Route::get('me', [AuthController::class, 'me']);
    });
  });

  Route::get('products', [ProductController::class, 'index']);
  Route::get('products/{id}', [ProductController::class, 'show']);
  Route::get('coming-products', [ComingProductController::class, 'index']);
  Route::post('orders', [OrderController::class, 'store']);
  Route::get('orders/{id}', [OrderController::class, 'show']);
  Route::get('orders/invoice/{invoiceNumber}', [InvoiceController::class, 'downloadByInvoiceNumber']);

  Route::prefix('admin')->middleware('jwt.auth')->group(function () {
    Route::get('orders', [AdminOrderController::class, 'index']);
    Route::patch('orders/{id}/status', [AdminOrderController::class, 'updateStatus']);
    Route::get('customers', [AdminCustomerController::class, 'index']);
    Route::get('customers/{id}', [AdminCustomerController::class, 'show']);
  });
});
