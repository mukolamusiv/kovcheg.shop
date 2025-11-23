<?php

use App\Http\Controllers\PDFController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Додаткові маршрути можна додати тут
Route::get('/pdf/material/{id}', [PDFController::class, 'generateMaterialPDF'])->name('pdf.material');
Route::get('/pdf/transactions/{account_id}/{date_start}/{date_finish}', [PDFController::class, 'generateTransactionAccountPDF'])->name('pdf.transactions');
