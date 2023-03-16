<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TransactionController;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/auth')->group(function () {
   Route::post('/login', [AuthController::class, 'login']);
});

Route::prefix('/user')->group(function () {
    Route::post('/', [AuthController::class, 'createUser']);
});

Route::prefix('/products')->group(function() {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{id}', [ProductController::class, 'show']);
    Route::post('/', [ProductController::class, 'store'])->middleware('auth:sanctum');;
    Route::patch('/{id}', [ProductController::class, 'update'])->middleware('auth:sanctum');;
    Route::delete('/', [ProductController::class, 'destroy'])->middleware('auth:sanctum');;
});

Route::post('/image',[ImageController::class, 'store'])->middleware('auth:sanctum');

Route::prefix('/transactions')->group(function () {
    Route::post('/', [TransactionController::class, 'store']);
    Route::get('/', [TransactionController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/{id}', [TransactionController::class, 'show'])->middleware('auth:sanctum');
});
