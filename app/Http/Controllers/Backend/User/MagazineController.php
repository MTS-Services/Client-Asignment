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
            'auth:admin', // Applies 'auth:admin' to all methods

            // Permission middlewares using the Middleware class
            new Middleware('permission:magazine-list', only: ['magazineList']),
            new Middleware('permission:magazine-details', only: ['magazineShow']),
            //add more permissions if needed
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function magazineList(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->magazineService->getMagazines();
            return DataTables::eloquent($query)
                ->editColumn('status', function ($magazine) {
                    return "<span class='badge badge-soft " . $magazine->status_color . "'>" . $magazine->status_label . "</span>";
                })
                ->editColumn('created_by', function ($magazine) {
                    return $this->creater_name($magazine);
                })
                ->editColumn('created_at', function ($magazine) {
                    return $magazine->created_at_formatted;
                })
                ->editColumn('action', function ($service) {
                    $menuItems = $this->menuItems($service);
                    return view('components.user.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['created_by', 'status', 'created_at', 'action'])
                ->make(true);
        }
        return view('backend.user.magazine.index');
    }

    protected function menuItems($model): array
    {
        return [
            [
                'routeName' => 'javascript:void(0)',
                'data-id' => encrypt($model->id),
                'className' => 'view',
                'label' => 'Details',
                'permissions' => ['permission-list']
            ],
        ];
    }


    /**
     * Display the specified resource.
     */
    public function magazineShow(Request $request, string $id)
    {
        $data = $this->magazineService->getMagazine($id);
        $data['creater_name'] = $this->creater_name($data);
        $data['updater_name'] = $this->updater_name($data);
        return response()->json($data);
    }
}

   