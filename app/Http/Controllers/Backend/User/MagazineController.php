<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\Http\Traits\AuditRelationTraits;
use App\Services\Admin\MagazineService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;

class MagazineController extends Controller implements HasMiddleware
{
    use AuditRelationTraits;

    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('magazine.index');
    }

    protected MagazineService $magazineService;

    public function __construct(MagazineService $magazineService)
    {
        $this->magazineService = $magazineService;
    }

    public static function middleware(): array
    {
        return [
            'auth:web',
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function magazineList(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->magazineService->getMagazines()->active();
            return DataTables::eloquent($query)
                ->editColumn('status', function ($magazine) {
                    return "<span class='badge badge-soft " . $magazine->status_color . "'>" . $magazine->status_label . "</span>";
                })
                ->editColumn('created_at', function ($magazine) {
                    return $magazine->created_at_formatted;
                })
                ->editColumn('action', function ($service) {
                    $menuItems = $this->menuItems($service);
                    return view('components.user.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['status', 'created_at', 'action'])
                ->make(true);
        }
        return view('backend.user.magazine.index');
    }

    protected function menuItems($model): array
    {
        return [
            [
                'routeName' => 'user.magazine-show',
                'params' => ['slug' => $model->slug],
                'className' => 'view',
                'label' => 'Details',
                'permissions' => ['permission-list']
            ],
        ];
    }


    /**
     * Display the specified resource.
     */
    public function magazineShow(string $slug)
    {
        $magazine = $this->magazineService->getMagazine($slug , 'slug');
        return view('backend.user.magazine.show', compact('magazine'));
    }
}

