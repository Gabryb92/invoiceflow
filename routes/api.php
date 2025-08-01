<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ClientController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\ProductController;



Route::prefix('v1')->post('/login', [AuthController::class, 'login'])->name('api.login');

//Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
Route::prefix('v1')->group(function(){

    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout')->middleware('auth:sanctum');

    Route::apiResource('clients', ClientController::class)->names('api.clients');
    Route::apiResource('products', ProductController::class)->names('api.products');
    Route::apiResource('invoices', InvoiceController::class)->names('api.invoices');

});