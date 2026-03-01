<?php

use App\Http\Controllers\Pages;
use App\Http\Controllers\PDFController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('layouts.store');
// });


Route::get('/demo', function () {
    return view('welcome');
});

Route::get('/', [Pages::class, 'home'])->name('home');
Route::get('/about', [Pages::class, 'about'])->name('about');
Route::get('/contact', [Pages::class, 'contact'])->name('contact');
Route::get('/product/{slug?}', [Pages::class, 'product'])->name('product');


// Додаткові маршрути можна додати тут
Route::get('/pdf/material/{id}', [PDFController::class, 'generateMaterialPDF'])->name('pdf.material');
Route::get('/pdf/transactions/{account_id}/{date_start}/{date_finish}', [PDFController::class, 'generateTransactionAccountPDF'])->name('pdf.transactions');
