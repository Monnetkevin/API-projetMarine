<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\ImageController;
use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CategoryController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// ROUTE AUTH
Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::get('/currentUser', 'currentUser')->middleware('auth:api');
    Route::post('/logout', 'logout')->middleware('auth:api');
});
// ROUTE CATEGORIES
Route::controller(CategoryController::class)->group(function () {
    Route::get('/categories', 'index');
    Route::post('/categories', 'store')->middleware('auth:api');
    Route::get('/categories/{category}', 'show')->middleware('auth:api');
    Route::post('/categories/{category}', 'update')->middleware('auth:api');
    Route::delete('/categories/{category}', 'destroy')->middleware('auth:api');
});
// ROUTE ADDRESS
Route::controller(AddressController::class)->group(function () {
    Route::get('/addresses', 'index')->middleware('auth:api');
    Route::post('/addresses', 'store')->middleware('auth:api');
    Route::get('/addresses/{address}', 'show')->middleware('auth:api');
    Route::post('/addresses/{address}', 'update')->middleware('auth:api');
    Route::delete('/addresses/{address}', 'destroy')->middleware('auth:api');
});
// ROUTE COMMENT
Route::controller(CommentController::class)->group(function () {
    Route::get('/comments', 'index');
    Route::post('/comments', 'store')->middleware('auth:api');
    Route::get('/comments/{comment}', 'show')->middleware('auth:api');
    Route::post('/comments/{comment}', 'update')->middleware('auth:api');
    Route::delete('/comments/{comment}', 'destroy')->middleware('auth:api');
});
// ROUTE EVENT
Route::controller(EventController::class)->group(function () {
    Route::get('/events', 'index');
    Route::post('/events', 'store')->middleware('auth:api');
    Route::get('/events/{event}', 'show')->middleware('auth:api');
    Route::post('/events/{event}', 'update')->middleware('auth:api');
    Route::delete('/events/{event}', 'destroy')->middleware('auth:api');
});
// ROUTE PRODUCT
Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'index');
    Route::get('/products/last', 'lastProduct');
    Route::post('/products', 'store')->middleware('auth:api');
    Route::get('/products/{product}', 'show')->middleware('auth:api');
    Route::post('/products/{product}', 'update')->middleware('auth:api');
    Route::delete('/products/{product}', 'destroy')->middleware('auth:api');
});
// ROUTE IMAGE
Route::controller(ImageController::class)->group(function () {
    Route::post('/images', 'store')->middleware('auth:api');
    Route::get('/images/{image}', 'show')->middleware('auth:api');
    Route::delete('/images/{image}', 'destroy')->middleware('auth:api');
});
