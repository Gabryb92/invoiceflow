<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ClientController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\ProductController;



Route::post('/v1/login', [AuthController::class, 'login'])->name('api.login');

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
//Route::prefix('v1')->group(function(){

    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout')->middleware('auth:sanctum');

    // --- ROTTE PERSONALIZZATE PER I CLIENTI ---
    
    Route::post('clients/{client}/anonymize', [ClientController::class, 'anonymize'])->name('api.clients.anonymize')->withTrashed();
    Route::post('clients/{client}/restore', [ClientController::class, 'restore'])->name('api.clients.restore')->withTrashed();
    Route::get('clients/{client}', [ClientController::class, 'show'])->name('api.clients.show')->withTrashed();


    // --- ROTTE PERSONALIZZATE PER I Prodotti ---

    Route::post('products/{product}/restore', [ProductController::class, 'restore'])->name('api.products.restore')->withTrashed();

    Route::delete('products/{product}/forceDelete', [ProductController::class, 'forceDelete'])->name('api.products.forceDelete')->withTrashed();

    Route::get('products/{product}', [ProductController::class, 'show'])->name('api.clients.show')->withTrashed();

    Route::apiResource('clients', ClientController::class)->except(['show'])->names('api.clients');
    Route::apiResource('products', ProductController::class)->except(['show'])->names('api.products');
    Route::apiResource('invoices', InvoiceController::class)->names('api.invoices');

});