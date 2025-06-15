<?php

use App\Http\Controllers\Backend\User\NewspaperController as UserNewspaperController;
use App\Http\Controllers\Backend\User\MagazineController as UserMagazineController;
use App\Http\Controllers\Backend\User\DashboardController as UserDashboardController;
use App\Http\Controllers\Backend\User\ProfileController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'user.', 'middleware' => ['auth:web', 'otp.verified']], function () {
    Route::get('/dashboard', [UserDashboardController::class, 'dashboard'])->name('dashboard');
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'showProfile')->name('profile');
        Route::get('/edit-profile', 'editProfile')->name('edit-profile');
        Route::put('/update-profile', 'updateProfile')->name('update-profile');
        Route::get('/change-password', 'showPasswordPage')->name('change-password');
        Route::put('/update-password', 'updatePassword')->name('update-password');
    });
    // Magazine
    Route::controller(UserMagazineController::class)->group(function () {
        Route::get('/magazine-list', 'magazineList')->name('magazine-list');
        Route::get('/show/{magazine}', 'magazineShow')->name('magazine-show');
    });
    // Newspaper
    Route::controller(UserNewspaperController::class)->group(function () {
        Route::get('/newspaper-list', 'newspaperList')->name('newspaper-list');
        Route::get('/show/{newspaper}', 'newspaperShow')->name('newspaper-show');
    });
});
