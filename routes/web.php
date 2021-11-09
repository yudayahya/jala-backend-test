<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SaleOrderController;
use App\Http\Controllers\UserAuth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return redirect('/login');
    });
    Route::get('/register', [UserAuth::class, 'register']);
    Route::post('/register', [UserAuth::class, 'register_proses']);
    Route::get('/login', [UserAuth::class, 'index'])->name('login');
    Route::post('/login', [UserAuth::class, 'login']);
});

Route::middleware(['auth:web'])->group(function () {
    Route::post('/logout', [UserAuth::class, 'logout']);

    Route::get('/home', [DashboardController::class, 'index']);

    Route::get('/product', [ProductController::class, 'index']);
    Route::get('/product/data', [ProductController::class, 'get_data']);

    Route::get('/sale', [SaleOrderController::class, 'index']);
    Route::post('/sale', [SaleOrderController::class, 'store']);
    Route::get('/sale/history', [SaleOrderController::class, 'history']);
    Route::get('/sale/history/{id}', [SaleOrderController::class, 'show']);
    Route::get('/sale/cart', [SaleOrderController::class, 'data_cart']);
    Route::post('/sale/cart', [SaleOrderController::class, 'add_cart']);
    Route::delete('/sale/cart', [SaleOrderController::class, 'destroy_cart']);
    Route::get('/sale/cart/total', [SaleOrderController::class, 'total_cart']);

    Route::middleware(['role:Super Admin'])->group(function () {
        Route::post('/product', [ProductController::class, 'store']);
        Route::delete('/product', [ProductController::class, 'destroy']);
        Route::get('/product/create', [ProductController::class, 'create']);
        Route::get('/product/{id}/edit', [ProductController::class, 'show']);
        Route::post('/product/update', [ProductController::class, 'update']);

        Route::get('/purchase', [PurchaseOrderController::class, 'index']);
        Route::post('/purchase', [PurchaseOrderController::class, 'store']);
        Route::get('/purchase/history', [PurchaseOrderController::class, 'history']);
        Route::get('/purchase/history/{invoice}', [PurchaseOrderController::class, 'show']);
        Route::get('/purchase/cart', [PurchaseOrderController::class, 'data_cart']);
        Route::post('/purchase/cart', [PurchaseOrderController::class, 'add_cart']);
        Route::delete('/purchase/cart', [PurchaseOrderController::class, 'destroy_cart']);

        Route::delete('/sale', [SaleOrderController::class, 'destroy']);
        Route::get('/sale/pending', [SaleOrderController::class, 'pending_data']);
        Route::put('/sale/pending', [SaleOrderController::class, 'pending_update']);
        Route::get('/sale/pending/count', [SaleOrderController::class, 'pending_count']);
    });
});
