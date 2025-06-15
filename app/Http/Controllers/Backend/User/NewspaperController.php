<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\Http\Traits\AuditRelationTraits;
use App\Services\Admin\NewspaperService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;

class NewspaperController extends Controller implements HasMiddleware
{
    use AuditRelationTraits;

    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('user.newspaper-list');
    }

    protected NewspaperService $newspaperService;

    public function __construct(NewspaperService $newspaperService)
    {
        $this->newspaperService = $newspaperService;
    }

    public static function middleware(): array
    {
        return [
            'auth:admin', // Applies 'auth:admin' to all methods

            // Permission middlewares using the Middleware class
            new Middleware('permission:newspaper-list', only: ['newspaperList']),
            new Middleware('permission:newspaper-details', only: ['newspaperShow']),
            //add more permissions if needed
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function newspaperList(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->newspaperService->getNewspapers();
            return DataTables::eloquent($query)
                ->editColumn('status', function ($newspaper) {
                    return "<span class='badge badge-soft " . $newspaper->status_color . "'>" . $newspaper->status_label . "</span>";
                })
                ->editColumn('created_by', function ($newspaper) {
                    return $this->creater_name($newspaper);
                })
                ->editColumn('created_at', function ($newspaper) {
                    return $newspaper->created_at_formatted;
                })
                ->editColumn('action', function ($newspaper) {
                    $menuItems = $this->menuItems($newspaper);
                    return view('components.user.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['created_by', 'status', 'created_at', 'action'])
                ->make(true);
        }
        return view('backend.user.newspaper.index');
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
            ]

        ];
    }

    /**
     * Display the specified resource.
     */
    public function newspaperShow(Request $request, string $id)
    {
        $data = $this->newspaperService->getNewspaper($id);
        $data['creater_name'] = $this->creater_name($data);
        $data['updater_name'] = $this->updater_name($data);
        return response()->json($data);
    }

   
}
