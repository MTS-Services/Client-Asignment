<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Admin\AdminManagement\RoleController;
use App\Http\Controllers\Backend\Admin\AdminManagement\AdminController;
use App\Http\Controllers\Backend\Admin\AdminManagement\PermissionController;
use App\Http\Controllers\Backend\Admin\AuthorController;
use App\Http\Controllers\Backend\Admin\BookController;
use App\Http\Controllers\Backend\Admin\CategoryManagement\CategoryController;
use App\Http\Controllers\Backend\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Backend\Admin\UserManagment\UserController;
use App\Http\Controllers\Backend\Admin\MagazineController;
use App\Http\Controllers\Backend\Admin\NewspaperController;
use App\Http\Controllers\Backend\Admin\PublishManagement\PublisherController;
use App\Http\Controllers\Backend\Admin\RackController;

Route::group(['middleware' => ['auth:admin', 'admin.verified'], 'prefix' => 'admin'], function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');

    // Admin Management
    Route::group(['as' => 'am.', 'prefix' => 'admin-management'], function () {
        // Admin Routes
        Route::resource('admin', AdminController::class);
        Route::controller(AdminController::class)->name('admin.')->prefix('admin')->group(function () {
            Route::post('/show/{admin}', 'show')->name('show');
            Route::get('/status/{admin}', 'status')->name('status');
            Route::get('/trash/bin', 'trash')->name('trash');
            Route::get('/restore/{admin}', 'restore')->name('restore');
            Route::delete('/permanent-delete/{admin}', 'permanentDelete')->name('permanent-delete');
        });
        // Role Routes
        Route::resource('role', RoleController::class);
        Route::controller(RoleController::class)->name('role.')->prefix('role')->group(function () {
            Route::post('/show/{role}', 'show')->name('show');
            Route::get('/trash/bin', 'trash')->name('trash');
            Route::get('/restore/{role}', 'restore')->name('restore');
            Route::delete('/permanent-delete/{role}', 'permanentDelete')->name('permanent-delete');
        });
        // Permission Routes
        Route::resource('permission', PermissionController::class);
        Route::controller(PermissionController::class)->name('permission.')->prefix('permission')->group(function () {
            Route::post('/show/{permission}', 'show')->name('show');
            Route::get('/trash/bin', 'trash')->name('trash');
            Route::get('/restore/{permission}', 'restore')->name('restore');
            Route::delete('/permanent-delete/{permission}', 'permanentDelete')->name('permanent-delete');
        });
        // Category Management Routes
        Route::resource('category', CategoryController::class);
        Route::controller(CategoryController::class)->name('category.')->prefix('category')->group(function () {
            Route::post('/show/{category}', 'show')->name('show');
            Route::get('/trash/bin', 'trash')->name('trash');
            Route::get('/restore/{category}', 'restore')->name('restore');
            Route::delete('/permanent-delete/{category}', 'permanentDelete')->name('permanent-delete');
        });
    });
    Route::group(['as' => 'um.', 'prefix' => 'user-management'], function () {
        Route::resource('user', UserController::class);
        Route::controller(UserController::class)->name('user.')->prefix('user')->group(function () {
            Route::get('/status/{user}', 'status')->name('status');
            Route::get('/trash/bin', 'trash')->name('trash');
            Route::post('/show/{user}', 'show')->name('show');
            Route::get('/restore/{user}', 'restore')->name('restore');
            Route::delete('/permanent-delete/{user}', 'permanentDelete')->name('permanent-delete');
        });
    });
    Route::group(['as' => 'pm.', 'prefix' => 'publish-management'], function () {
        // Publisher Routes
        Route::resource('publisher', PublisherController::class);
        Route::controller(PublisherController::class)->name('publisher.')->prefix('publisher')->group(function () {
            Route::post('/show/{publisher}', 'show')->name('show');
            Route::get('/status/{publisher}', 'status')->name('status');
            Route::get('/trash/bin', 'trash')->name('trash');
            Route::get('/restore/{publisher}', 'restore')->name('restore');
            Route::delete('/permanent-delete/{publisher}', 'permanentDelete')->name('permanent-delete');
        });
    });
    // Author Management
    Route::resource('author', AuthorController::class);
    Route::controller(AuthorController::class)->name('author.')->prefix('author')->group(function () {
        Route::post('/show/{author}', 'show')->name('show');
        Route::get('/status/{author}', 'status')->name('status');
        Route::get('/trash/bin', 'trash')->name('trash');
        Route::get('/restore/{author}', 'restore')->name('restore');
        Route::delete('/permanent-delete/{author}', 'permanentDelete')->name('permanent-delete');
    });

    // Rack Management
    Route::resource('rack', RackController::class);
    Route::controller(RackController::class)->name('rack.')->prefix('rack')->group(function () {
        Route::post('/show/{rack}', 'show')->name('show');
        Route::get('/trash/bin', 'trash')->name('trash');
        Route::get('/restore/{rack}', 'restore')->name('restore');
        Route::delete('/permanent-delete/{rack}', 'permanentDelete')->name('permanent-delete');
    });

    Route::resource('magazine', MagazineController::class);
    Route::controller(MagazineController::class)->name('magazine.')->prefix('magazine')->group(function () {
        Route::post('/show/{magazine}', 'show')->name('show');
        Route::get('/status/{magazine}', 'status')->name('status');
        Route::get('/trash/bin', 'trash')->name('trash');
        Route::get('/restore/{magazine}', 'restore')->name('restore');
        Route::delete('/permanent-delete/{magazine}', 'permanentDelete')->name('permanent-delete');
    });
    Route::resource('newspaper', NewspaperController::class);
    Route::controller(NewspaperController::class)->name('newspaper.')->prefix('newspaper')->group(function () {
        Route::post('/show/{newspaper}', 'show')->name('show');
        Route::get('/status/{newspaper}', 'status')->name('status');
        Route::get('/trash/bin', 'trash')->name('trash');
        Route::get('/restore/{newspaper}', 'restore')->name('restore');
        Route::delete('/permanent-delete/{newspaper}', 'permanentDelete')->name('permanent-delete');
    });
    Route::resource('book', BookController::class);
    Route::controller(BookController::class)->name('book.')->prefix('book')->group(function () {
        Route::post('/show/{book}', 'show')->name('show');
        Route::get('/status/{book}', 'status')->name('status');
        Route::get('/trash/bin', 'trash')->name('trash');
        Route::get('/restore/{book}', 'restore')->name('restore');
        Route::delete('/permanent-delete/{book}', 'permanentDelete')->name('permanent-delete');
    });

});
