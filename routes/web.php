<?php

use App\Http\Controllers\Api\InvoiceController;
use Illuminate\Support\Facades\Route;

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

// Invoice download route for Filament (protected by Filament auth)
Route::middleware(['web'])->group(function () {
  Route::get('/admin/orders/{order}/invoice', [InvoiceController::class, 'download'])
    ->name('filament.orders.invoice')
    ->middleware('auth');
});



