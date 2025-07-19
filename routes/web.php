<?php

use App\Livewire\TestPaginator;
use App\Livewire\Clients\ClientForm;
use App\Livewire\Clients\ClientList;
use Illuminate\Support\Facades\Route;
use App\Livewire\Invoices\InvoiceForm;
use App\Livewire\Invoices\InvoiceList;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InvoicePdfController;

Route::get('/login', function () {
    return view('login');
});


Route::middleware(['auth','verified'])->prefix('dashboard')->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    //Clienti
    Route::get('/clienti',ClientList::class)->name('clienti.index');

    //Il componente livewire è lo stesso per entrambi i casi!
    Route::get('/clienti/create', ClientForm::class)->name('clienti.create');
    Route::get('/clienti/{client}/edit', ClientForm::class)->name('clienti.edit');

    //Fatture
    Route::get('/fatture',InvoiceList::class)->name('fatture.index');
    Route::get('/fatture/create', InvoiceForm::class)->name('fatture.create');
    Route::get('/fatture/{invoice}/edit', InvoiceForm::class)->name('fatture.edit');

    //PDF Fatture
    Route::get('/fatture/{invoice}/pdf', [InvoicePdfController::class, 'downloadPdf'])->name('fatture.pdf');
});
    

 

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
