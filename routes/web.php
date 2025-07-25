<?php

use App\Livewire\TestPaginator;
use App\Livewire\Clients\ClientForm;
use App\Livewire\Clients\ClientList;
use App\Livewire\Clients\ClientShow;
use Illuminate\Support\Facades\Route;
use App\Livewire\Invoices\InvoiceForm;
use App\Livewire\Invoices\InvoiceList;
use App\Livewire\Products\ProductForm;
use App\Livewire\Products\ProductList;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InvoicePdfController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Livewire\Dashboard\Dashboard;
use App\Livewire\Invoices\InvoiceShow;

Route::get('/', [AuthenticatedSessionController::class, 'create'])
        ->name('login');


Route::middleware(['auth','verified'])->prefix('dashboard')->group(function () {
    // Route::get('/', function () {
    //     return view('dashboard');
    // })->name('dashboard');

    Route::get('/',Dashboard::class)->name('dashboard');

    //Clienti
    Route::get('/clienti',ClientList::class)->name('clienti.index');

    //Il componente livewire è lo stesso per entrambi i casi!
    Route::get('/clienti/create', ClientForm::class)->name('clienti.create');
    Route::get('/clienti/{client}/edit', ClientForm::class)->name('clienti.edit');

    //Pagina dettaglio Cliente (Show)
    Route::get('/clienti/{client}', ClientShow::class)->name('clienti.show');
    

    //Fatture
    Route::get('/fatture',InvoiceList::class)->name('fatture.index');
    Route::get('/fatture/create', InvoiceForm::class)->name('fatture.create');
    Route::get('/fatture/{invoice}/edit', InvoiceForm::class)->name('fatture.edit');

    //Pagina dettaglio Fattura (Show)
    Route::get('/fatture/{invoice}', InvoiceShow::class)->name('fatture.show');

    //PDF Fatture
    Route::get('/fatture/{invoice}/pdf', [InvoicePdfController::class, 'downloadPdf'])->name('fatture.pdf');

    //Prodotti
    Route::get('/prodotti', ProductList::class)->name('prodotti.index');
    Route::get('/prodotti/create', ProductForm::class)->name('prodotti.create');
    Route::get('/prodotti/{products}/edit', ProductForm::class)->name('prodotti.edit');
});
    

 

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
