<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Traits\AuditRelationTraits;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use App\Models\ApplicationSetting;

class ApplicationSettingController extends Controller implements HasMiddleware
{
    use AuditRelationTraits;

    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('index route');
    }

    protected function redirectTrashed(): RedirectResponse
    {
        return redirect()->route('trash route');
    }

    public static function middleware(): array
    {
        return [
            'auth:admin', // Applies 'auth:admin' to all methods

            // Permission middlewares using the Middleware class
            new Middleware('permission:application-settings-list', only: ['index']),
            //add more permissions if needed
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function general(): View
    {
        $data['general_settings'] = ApplicationSetting::whereIn('key', ['site_name', 'site_short_name', 'timezone', 'site_logo', 'site_favicon', 'env', 'debug', 'debugbar', 'date_format', 'time_format'])->pluck('value', 'key')->all();
        $data['timezones'] = availableTimezones();
        return View('backend.admin.application-settings.general', $data);
    }
}
