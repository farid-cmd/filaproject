<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\LogbookPrintController;

// 🔹 Halaman login (GET + POST)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// 🔹 Proses logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/', function () {
    return redirect('/login');
});


Route::get('/logbooks/print/{id}', [LogbookPrintController::class, 'print'])
    ->name('filament.resources.logbooks.print');
