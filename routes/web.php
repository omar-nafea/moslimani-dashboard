<?php

use App\Http\Controllers\Api\InvoiceController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
  return response()->json([
    'name' => config('app.name'),
    'version' => '1.0.0',
    'status' => 'running',
  ]);
});

// CORS headers for images - shared between GET and OPTIONS
$imagesCorsHeaders = [
  'Access-Control-Allow-Origin' => '*',
  'Access-Control-Allow-Methods' => 'GET, HEAD, OPTIONS',
  'Access-Control-Allow-Headers' => '*',
  'Access-Control-Max-Age' => '86400',
  'Cross-Origin-Resource-Policy' => 'cross-origin',
];

// Handle OPTIONS preflight requests for images
Route::options('/images/{path}', function () use ($imagesCorsHeaders) {
  return response('', 204, $imagesCorsHeaders);
})->where('path', '.*');

// Serve storage images with CORS headers (for ngrok and cross-origin access)
Route::get('/images/{path}', function (string $path) use ($imagesCorsHeaders) {
  $fullPath = storage_path('app/public/' . $path);
  
  if (!file_exists($fullPath)) {
    abort(404);
  }
  
  $mimeType = mime_content_type($fullPath);
  
  return response()->file($fullPath, array_merge($imagesCorsHeaders, [
    'Content-Type' => $mimeType,
    'Cache-Control' => 'public, max-age=31536000',
  ]));
})->where('path', '.*');

// Invoice download route for Filament (protected by Filament auth)
Route::middleware(['web'])->group(function () {
  Route::get('/admin/orders/{order}/invoice', [InvoiceController::class, 'download'])
    ->name('filament.orders.invoice')
    ->middleware('auth');
});



