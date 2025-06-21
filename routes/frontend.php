<?php

use App\Http\Controllers\Frontend\EnquiryController;
use App\Http\Controllers\Frontend\HomeController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'f.'], function () {
    Route::get('/', [HomeController::class, 'home'])->name('home');
    Route::get('/enquiry', [EnquiryController::class, 'enquiry'])->name('enquiry');
    Route::post('/enquiry', [EnquiryController::class, 'store'])->name('enquiry-store');
});
