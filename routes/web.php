<?php

use App\Livewire\TestPaginator;
use App\Livewire\Clients\ClientForm;
use App\Livewire\Clients\ClientList;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth','verified'])->prefix('dashboard')->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    //Clienti
    Route::get('/clienti',ClientList::class)->name('clienti.index');
    Route::get('/clienti/create', ClientForm::class)->name('clienti.create');
});
    
 

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
