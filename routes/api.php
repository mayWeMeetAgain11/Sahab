<?php

use App\Http\Controllers\AmenityController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\PlaceImageController;
use App\Http\Controllers\PromoCodeController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceImageController;
use App\Http\Controllers\SpecialDayController;
use App\Http\Controllers\StaticContentController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('categories')->group(function () {
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
        Route::get('/{id}/places', [CategoryController::class, 'getPlacesForOneCategory']);
        Route::get('/{id}/services', [CategoryController::class, 'getServicesForOneCategory']);
        Route::get('/{id}', [CategoryController::class, 'show']);
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/type', [CategoryController::class, 'getAllDependingOnType']);
});

Route::prefix('places')->group(function () {

    Route::post('/', [PlaceController::class, 'store']);
    Route::put('/{id}', [PlaceController::class, 'update']);
    Route::delete('/{id}', [PlaceController::class, 'destroy']);
    Route::get('/{id}', [PlaceController::class, 'show']);
    Route::get('/', [PlaceController::class, 'index']);
    Route::get('/featured', [PlaceController::class, 'getAllFeatured']);
    Route::get('/users/get-all', [PlaceController::class, 'getPlacesForOneUser'])->middleware('auth:sanctum', 'role:vendor');

    Route::prefix('/images')->group(function () {
        Route::post('/', [PlaceImageController::class, 'store']);
        // Route::put('/{id}', [PlaceImageController::class, 'update']);
        Route::delete('/delete-many', [PlaceImageController::class, 'deleteMany']);
        Route::delete('/{id}', [PlaceImageController::class, 'destroy']);
        Route::get('/{id}', [PlaceImageController::class, 'getAllForOnePlace']);
    });

});

Route::prefix('services')->group(function () {
    Route::post('/', [ServiceController::class, 'store']);
    Route::put('/{id}', [ServiceController::class, 'update']);
    Route::delete('/{id}', [ServiceController::class, 'destroy']);
    Route::get('/{id}', [ServiceController::class, 'show']);
    Route::get('/', [ServiceController::class, 'index']);
    Route::get('/featured', [ServiceController::class, 'getAllFeatured']);
    Route::get('/users/{id}', [ServiceController::class, 'getServicesForOneUser']);

    Route::prefix('/images')->group(function () {
        Route::delete('/delete-many', [ServiceImageController::class, 'deleteMany']);
        Route::post('/', [ServiceImageController::class, 'store']);
        Route::delete('/{id}', [ServiceImageController::class, 'destroy']);
        Route::get('/{id}', [ServiceImageController::class, 'getAllForOneService']);
    });

});

Route::prefix('amenities')->group(function () {
    Route::post('/', [AmenityController::class, 'store']);
    Route::put('/{id}', [AmenityController::class, 'update']);
    Route::delete('/{id}', [AmenityController::class, 'destroy']);
    Route::get('/{id}', [AmenityController::class, 'show']);
    Route::get('/', [AmenityController::class, 'index']);
});

Route::prefix('special-days')->group(function () {
    Route::post('/', [SpecialDayController::class, 'store']);
    Route::put('/{id}', [SpecialDayController::class, 'update']);
    Route::delete('/{id}', [SpecialDayController::class, 'destroy']);
    Route::get('/{id}', [SpecialDayController::class, 'show']);
    Route::get('/', [SpecialDayController::class, 'index']);
});

Route::prefix('ratings')->group(function () {
    // Route::post('/', [RatingController::class, 'store']);
    Route::post('/', [RatingController::class, 'store'])->middleware('auth:sanctum', 'role:user');
    Route::put('/{id}', [RatingController::class, 'update']);
    Route::delete('/{id}', [RatingController::class, 'destroy']);
    Route::get('/{id}', [RatingController::class, 'show']);
    Route::get('/', [RatingController::class, 'index']);
});

Route::prefix('promo-codes')->group(function () {
    Route::post('/', [PromoCodeController::class, 'store']);
    Route::post('/add-many', [PromoCodeController::class, 'storeMany']);
    Route::put('/{id}', [PromoCodeController::class, 'update']);
    Route::put('/{id}/apply', [PromoCodeController::class, 'apply']);
    Route::delete('/{id}', [PromoCodeController::class, 'destroy']);
    Route::get('/{id}', [PromoCodeController::class, 'show']);
    Route::get('/', [PromoCodeController::class, 'index']);
});

Route::prefix('users')->group(function () {
    Route::post('/', [UserController::class, 'store']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::get('/', [UserController::class, 'index']);
    Route::post('/login', [UserController::class, 'login']);
});

Route::prefix('static-contents')->group(function () {
    Route::post('/', [StaticContentController::class, 'store']);
    Route::put('/{id}', [StaticContentController::class, 'update']);
    Route::get('/', [StaticContentController::class, 'index']);
});

Route::prefix('bookings')->group(function () {
    Route::delete('/{id}', [BookingController::class, 'destroy']);
    Route::get('/{id}', [BookingController::class, 'show']);
    Route::get('/', [BookingController::class, 'index']);
    Route::get('/future', [BookingController::class, 'getAllForFuture']);
    Route::get('/user', [BookingController::class, 'getAllForAuthUser'])->middleware('auth:api', 'role:user');
    Route::get('/cancel/{id}', [BookingController::class, 'cancelBooking'])->middleware('auth:api');
});

Route::prefix('managers')->group(function () {
    Route::post('/', [ManagerController::class, 'store']);
    Route::put('/{id}', [ManagerController::class, 'update']);
    Route::delete('/{id}', [ManagerController::class, 'destroy']);
    Route::get('/', [ManagerController::class, 'index']);
    Route::post('/login', [ManagerController::class, 'login']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
