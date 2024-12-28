<?php

use App\Http\Controllers\ShortUrlController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ShortUrlController::class, 'index'])->name('short-url.index');
Route::post('/short-url', [ShortUrlController::class, 'store'])->name('short-url.store');
Route::get('/{shortCode}', [ShortUrlController::class, 'redirect'])->name('short-url.redirect');


