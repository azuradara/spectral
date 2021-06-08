<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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
Route::post('/auth/login', [AuthController::class], 'login');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/fav', [FavoriteController::class, 'store']);
    Route::get('/fav', [FavoriteController::class, 'seek']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});