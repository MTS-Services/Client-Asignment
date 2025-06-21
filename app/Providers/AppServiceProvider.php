<?php

namespace App\Providers;

use App\Models\BookIssues;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Concurrency;
use Log;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::componentNamespace('App\\View\\Components\\Admin', 'admin');
        Blade::componentNamespace('App\\View\\Components\\User', 'user');
        Blade::componentNamespace('App\\View\\Components\\Frontend', 'frontend');
        Model::preventLazyLoading();
        Model::automaticallyEagerLoadRelationships();
        Gate::before(fn($admin, $ability) => $admin->hasRole('Super Admin') ? true : null);
        if (in_array(request()->segment(1), ['admin', 'user'])) {
            Log::info('Book overdue updated');
            BookIssues::where('due_date', '<', now())->update(['status' => BookIssues::STATUS_OVERDUE]);
        }
    }
}
