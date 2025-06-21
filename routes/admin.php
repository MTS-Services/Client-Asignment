<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Admin\AdminManagement\RoleController;
use App\Http\Controllers\Backend\Admin\AdminManagement\AdminController;
use App\Http\Controllers\Backend\Admin\AdminManagement\PermissionController;
use App\Http\Controllers\Backend\Admin\BookManagement\AuthorController;
use App\Http\Controllers\Backend\Admin\BookManagement\BookController;
use App\Http\Controllers\Backend\Admin\BookManagement\CategoryController;
use App\Http\Controllers\Backend\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Backend\Admin\IssuesManagement\BookIssuesController;
use App\Http\Controllers\Backend\Admin\UserManagement\UserController;
use App\Http\Controllers\Backend\Admin\MagazineController;
use App\Http\Controllers\Backend\Admin\NewspaperController;
use App\Http\Controllers\Backend\Admin\ProfileController;
use App\Http\Controllers\Backend\Admin\ApplicationSettingController;
use App\Http\Controllers\Backend\Admin\BookManagement\PublisherController;
use App\Http\Controllers\Backend\Admin\BookManagement\RackController;
use App\Http\Controllers\Backend\Admin\UserManagement\QueryController;

Route::group(['middleware' => ['auth:admin', 'admin.verified'], 'prefix' => 'admin'], function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');
    // Profile Management
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'showProfile')->name('profile');
        Route::put('/update-profile/{id}', 'updateProfile')->name('update-profile');
        Route::get('/change-password', 'showPasswordPage')->name('change-password');
        Route::put('/update-password/{id}', 'updatePassword')->name('update-password');
    });
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
        Route::controller(QueryController::class)->name('query.')->prefix('query')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::POST('/show/{query}', 'show')->name('show');
        });
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
    Route::group(['as' => 'bm.', 'prefix' => 'book-management'], function () {
        Route::resource('book', BookController::class);
        Route::controller(BookController::class)->name('book.')->prefix('book')->group(function () {
            Route::post('/show/{book}', 'show')->name('show');
            Route::get('/status/{book}', 'status')->name('status');
            Route::get('/trash/bin', 'trash')->name('trash');
            Route::get('/restore/{book}', 'restore')->name('restore');
            Route::delete('/permanent-delete/{book}', 'permanentDelete')->name('permanent-delete');
        });
        // Category Management Routes
        Route::resource('category', CategoryController::class);
        Route::controller(CategoryController::class)->name('category.')->prefix('category')->group(function () {
            Route::post('/show/{category}', 'show')->name('show');
            Route::get('/status/{category}', 'status')->name('status');
            Route::get('/trash/bin', 'trash')->name('trash');
            Route::get('/restore/{category}', 'restore')->name('restore');
            Route::delete('/permanent-delete/{category}', 'permanentDelete')->name('permanent-delete');
        });
        //Book Issue
        Route::resource('book-issues', BookIssuesController::class);
        Route::controller(BookIssuesController::class)->name('book-issues.')->prefix('book-issues')->group(function () {
            Route::post('/show/{bookIssue}', 'show')->name('show');
            Route::get('/status/{bookIssue}/{status}', 'status')->name('status');
            Route::get('/trash/bin', 'trash')->name('trash');
            Route::get('/return/{bookIssue}', 'return')->name('return');
            Route::get('/restore/{bookIssue}', 'restore')->name('restore');
            Route::delete('/permanent-delete/{bookIssue}', 'permanentDelete')->name('permanent-delete');
            Route::patch('/update-return/{bookIssue}', 'updateReturn')->name('update-return');
            Route::get('/fine-status/{bookIssue}/{status}', 'fineStatus')->name('fine-status');
        });
        // Publisher Routes
        Route::resource('publisher', PublisherController::class);
        Route::controller(PublisherController::class)->name('publisher.')->prefix('publisher')->group(function () {
            Route::post('/show/{publisher}', 'show')->name('show');
            Route::get('/status/{publisher}', 'status')->name('status');
            Route::get('/trash/bin', 'trash')->name('trash');
            Route::get('/restore/{publisher}', 'restore')->name('restore');
            Route::delete('/permanent-delete/{publisher}', 'permanentDelete')->name('permanent-delete');
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
    });


    Route::group(['as' => 'bim.', 'prefix' => 'book-issue-management'], function () {
        //Book Issue
        Route::resource('book-issues', BookIssuesController::class);
        Route::controller(BookIssuesController::class)->name('book-issues.')->prefix('book-issues')->group(function () {
            Route::post('/show/{bookIssue}', 'show')->name('show');
            Route::get('/status/{bookIssue}/{status}', 'status')->name('status');
            Route::get('/trash/bin', 'trash')->name('trash');
            Route::get('/return/{bookIssue}', 'return')->name('return');
            Route::get('/lost/{bookIssue}', 'lost')->name('lost');
            Route::get('/restore/{bookIssue}', 'restore')->name('restore');
            Route::delete('/permanent-delete/{bookIssue}', 'permanentDelete')->name('permanent-delete');
            Route::patch('/update-return/{bookIssue}', 'updateReturn')->name('update-return');
            Route::post('/update-return/{bookIssue}', 'updateLost')->name('update-lost');
        });
    });


    // Application Settings 
    Route::controller(ApplicationSettingController::class)->name('app-settings.')->prefix('application-settings')->group(function () {
        Route::post('/update-settings', 'updateSettings')->name('update-settings');
        Route::get('/', 'general')->name('general');
        Route::get('/database', 'database')->name('database');
        Route::get('/smtp', 'smtp')->name('smtp');
    });
});
