<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register']);

    Route::group(['middleware' => 'auth:sanctum'], function() {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
    });
});

Route::group(['prefix' => 'v1', 'middleware' => 'auth:sanctum'], function () {
    Route::resource('categories', \App\Http\Controllers\CategoryController::class)
        ->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::resource('transactions', \App\Http\Controllers\TransactionController::class)
        ->only(['index', 'store', 'show', 'update', 'destroy']);

    Route::get('transactions/{id}/payments', [\App\Http\Controllers\TransactionController::class, 'getPayments']);
    Route::post('transactions/{id}/payments', [\App\Http\Controllers\TransactionController::class, 'addPayment']);
    Route::delete('transactions/{id}/payments/{pid}', [\App\Http\Controllers\TransactionController::class, 'removePayment']);

    Route::get('report', [\App\Http\Controllers\ReportController::class, 'index']);
});



