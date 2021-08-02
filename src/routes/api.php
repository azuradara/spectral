<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\TaskCategoryController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WeatherController;

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

    /** BOOKMARKS
     * TODO: Rename to bookmarks instead of favs
     * TODO: Put queries in try:catch blocks
     */

    Route::get('/fav', [FavoriteController::class, 'seek']);
    Route::post('/fav', [FavoriteController::class, 'store']);
    Route::delete('/fav/{id}', [FavoriteController::class, 'delete']);
    Route::put('/fav/{id}', [FavoriteController::class, 'update']);
    Route::put('/fav/pin/{id}', [FavoriteController::class, 'pin']);
    Route::get('fav/pinned', [FavoriteController::class, 'getPinned']);

    /** CATEGORIES */

    Route::post('/cat', [CategoryController::class, 'store']);
    Route::get('/cat', [CategoryController::class, 'index']);
    Route::put('/cat/{id}', [CategoryController::class, 'update']);
    Route::delete('/cat/{id}', [CategoryController::class, 'delete']);

    /** TASK CATEGORIES */

    Route::get('/task_cat', [TaskCategoryController::class, 'get']);
    Route::post('/task_cat', [TaskCategoryController::class, 'create']);
    Route::put('/task_cat/{id}', [TaskCategoryController::class, 'update']);
    Route::delete('/task_cat/{id}', [TaskCategoryController::class, 'delete']);

    /** TASKS */

    Route::get('/task', [TaskController::class, 'get']);
    Route::post('/task', [TaskController::class, 'create']);
    Route::put('/task/{id}', [TaskController::class, 'update']);
    Route::delete('/task/{id}', [TaskController::class, 'delete']);

    /** WEATHER */
    Route::get('/weather', [WeatherController::class, 'index']);


    /** USER | EXPERIMENTAL */

    Route::put('/auth/user/tag', [AuthController::class, 'changeTag']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});