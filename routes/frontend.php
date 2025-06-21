<?php

use App\Http\Controllers\Frontend\QueryController;
use App\Http\Controllers\Frontend\HomeController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'f.'], function () {
    Route::get('/', [HomeController::class, 'home'])->name('home');
    Route::get('/enquiry', [QueryController::class, 'enquiry'])->name('enquiry');
    Route::post('/enquiry', [QueryController::class, 'store'])->name('enquiry-store');
});
