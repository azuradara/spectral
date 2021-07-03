<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FavoriteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// public routes

Route::post('/auth/signup', [AuthController::class, 'signup']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/fav', [FavoriteController::class, 'seek']);
    Route::post('/fav', [FavoriteController::class, 'store']);
    Route::delete('/fav/{id}', [FavoriteController::class, 'delete']);

    Route::put('/fav/pin/{id}', [FavoriteController::class, 'pin']);

    Route::get('fav/pinned', [FavoriteController::class, 'getPinned']);

    Route::post('/cat', [CategoryController::class, 'store']);
    Route::get('/cat', [CategoryController::class, 'index']);

    Route::put('/auth/user/tag', [AuthController::class, 'changeTag']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});