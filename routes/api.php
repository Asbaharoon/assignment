<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\postController;

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
Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/items', [ItemController::class, 'index']);
    Route::post('/items', [ItemController::class, 'store']);
    Route::get('/items/{item}', [ItemController::class, 'show']);
    Route::put('/items/{id}', [ItemController::class, 'update']);
    Route::delete('/items/{id}', [ItemController::class, 'delete']);
    /////////////////////////////////////
    Route::get('/total', [ItemController::class, 'totalItemsCount']);
    Route::get('/average/{item}', [ItemController::class, 'averagePrice']);
    Route::get('/websitehighestprice', [ItemController::class, 'websiteHighestTotalPrice']);
    Route::get('/totalpricethismonth', [ItemController::class, 'totalPriceThisMonth']);

    });

//////////////////////////////////////

Route::prefix('auth')->group(function () {

    Route::post('/register', [AuthController::class, 'createUser']);
    Route::post('/login', [AuthController::class, 'loginUser']);

});
