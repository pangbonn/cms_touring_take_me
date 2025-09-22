<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WebConfigController;
use App\Http\Controllers\Api\TripController;
use App\Http\Controllers\Api\BookingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('api')->group(function () {
    
    // Web Configuration API
    Route::prefix('web-config')->group(function () {
        Route::get('/', [WebConfigController::class, 'index']);
        Route::get('/contact', [WebConfigController::class, 'contact']);
        Route::get('/social', [WebConfigController::class, 'social']);
        Route::get('/company', [WebConfigController::class, 'company']);
        Route::get('/{key}', [WebConfigController::class, 'show']);
        Route::put('/', [WebConfigController::class, 'update']);
        Route::post('/reset', [WebConfigController::class, 'reset']);
    });

    // Trip API
    Route::prefix('trips')->group(function () {
        Route::get('/', [TripController::class, 'index']);
        Route::get('/search', [TripController::class, 'search']);
        Route::get('/upcoming', [TripController::class, 'upcoming']);
        Route::get('/available-months', [TripController::class, 'availableMonths']);
        Route::get('/price-range', [TripController::class, 'priceRange']);
        Route::get('/{id}', [TripController::class, 'show']);
        Route::get('/{id}/schedules', [TripController::class, 'schedules']);
        Route::get('/{tripId}/schedules/{scheduleId}', [TripController::class, 'schedule']);
    });

    // Booking API
    Route::prefix('bookings')->group(function () {
        Route::post('/', [BookingController::class, 'store']);
        Route::get('/{bookingId}', [BookingController::class, 'show']);
    });

});

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'version' => '1.0.0'
    ]);
});
