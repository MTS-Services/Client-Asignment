<?php

namespace App\Http\Controllers\Backend\Admin\BookManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RackRequest;
use App\Http\Traits\AuditRelationTraits;
use App\Services\Admin\RackService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;

class RackController extends Controller implements HasMiddleware
{
    use AuditRelationTraits;

    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('bm.rack.index');
    }

    protected function redirectTrashed(): RedirectResponse
    {
        return redirect()->route('bm.rack.trash');
    }

    protected RackService $rackService;

    public function __construct(RackService $rackService)
    {
        $this->rackService = $rackService;
    }

    public static function middleware(): array
    {
        return [
            'auth:admin', // Applies 'auth:admin' to all methods

            // Permission middlewares using the Middleware class
            new Middleware('permission:rack-list', only: ['index']),
            new Middleware('permission:rack-details', only: ['show']),
            new Middleware('permission:rack-create', only: ['create', 'store']),
            new Middleware('permission:rack-edit', only: ['edit', 'update']),
            new Middleware('permission:rack-delete', only: ['destroy']),
            new Middleware('permission:rack-trash', only: ['trash']),
            new Middleware('permission:rack-restore', only: ['restore']),
            new Middleware('permission:rack-permanent-delete', only: ['permanentDelete']),
            //add more permissions if needed
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->rackService->getRacks();
            return DataTables::eloquent($query)
                ->editColumn('created_by', fn($rack) => $this->creater_name($rack))
                ->editColumn('created_at', fn($rack) => $rack->created_at_formatted)
                ->editColumn('action', fn($rack) => view('components.admin.action-buttons', [
                    'menuItems' => $this->menuItems($rack)
                ])->render())
                ->rawColumns(['created_by', 'created_at', 'action'])
                ->make(true);
        }
        return view('backend.admin.book-management.rack.index');
    }


    protected function menuItems($model): array
    {
        return [
            [
                'routeName' => 'javascript:void(0)',
                'data-id' => encrypt($model->id),
                'className' => 'view',
                'label' => 'Details',
                'permissions' => ['rack-list', 'rack-delete', 'rack-status']
            ],
            [
                'routeName' => 'bm.rack.edit',
                'params' => [encrypt($model->id)],
                'label' => 'Edit',
                'permissions' => ['rack-edit']
            ],

            [
                'routeName' => 'bm.rack.destroy',
                'params' => [encrypt($model->id)],
                'label' => 'Delete',
                'delete' => true,
                'permissions' => ['rack-delete']
            ]

        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        //
        return view('backend.admin.book-management.rack.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RackRequest $request)
    {
        try {
            $validated = $request->validated();
            $this->rackService->createRack($validated);
            session()->flash('success', "Rack created successfully");
        } catch (\Throwable $e) {
            session()->flash('error', "Rack creation failed");
            throw $e;
        }
        return $this->redirectIndex();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $data = $this->rackService->getRack($id);
        $data['creater_name'] = $this->creater_name($data);
        $data['updater_name'] = $this->updater_name($data);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $data['rack'] = $this->rackService->getRack($id);
        return view('backend.admin.book-management.rack.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RackRequest $request, string $id)
    {
        try {
            $rack = $this->rackService->getRack($id);
            $validated = $request->validated();
            $this->rackService->updateRack($rack, $validated);
            session()->flash('success', "Rack updated successfully");
        } catch (\Throwable $e) {
            session()->flash('error', "Rack update failed");
            throw $e;
        }
        return $this->redirectIndex();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $rack = $this->rackService->getRack($id);
            $this->rackService->delete($rack);
            session()->flash('success', "Rack deleted successfully");
        } catch (\Throwable $e) {
            session()->flash('error', "Rack delete failed");
            throw $e;
        }
        return $this->redirectIndex();
    }

    public function trash(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->rackService->getRacks()->onlyTrashed();
            return DataTables::eloquent($query)
                ->editColumn('deleted_by', fn($rack) => $this->deleter_name($rack))
                ->editColumn('deleted_at', fn($rack) => $rack->deleted_at_formatted)
                ->editColumn('action', fn($rack) => view('components.admin.action-buttons', [
                    'menuItems' => $this->trashedMenuItems($rack)
                ])->render())
                ->rawColumns(['deleted_by', 'deleted_at', 'action'])
                ->make(true);
        }
        return view('backend.admin.book-management.rack.trash');
    }


    protected function trashedMenuItems($model): array
    {
        return [
            [
                'routeName' => 'bm.rack.restore',
                'params' => [encrypt($model->id)],
                'label' => 'Restore',
                'permissions' => ['permission-restore']
            ],
            [
                'routeName' => 'bm.rack.permanent-delete',
                'params' => [encrypt($model->id)],
                'label' => 'Permanent Delete',
                'p-delete' => true,
                'permissions' => ['permission-permanent-delete']
            ]

        ];
    }

    public function restore(string $id): RedirectResponse
    {
        try {
            $this->rackService->restore($id);
            session()->flash('success', "Rack restored successfully");
        } catch (\Throwable $e) {
            session()->flash('error', "Rack restore failed");
            throw $e;
        }
        return $this->redirectTrashed();
    }

    public function permanentDelete(string $id): RedirectResponse
    {
        try {
            $this->rackService->permanentDelete($id);
            session()->flash('success', "Rack permanently deleted successfully");
        } catch (\Throwable $e) {
            session()->flash('error', "Rack permanent delete failed");
            throw $e;
        }
        return $this->redirectTrashed();
    }
}
