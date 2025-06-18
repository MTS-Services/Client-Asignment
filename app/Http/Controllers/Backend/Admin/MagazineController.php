<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MagazineRequest;
use App\Http\Traits\AuditRelationTraits;
use App\Models\Magazine;
use App\Services\Admin\MagazineService;
use Illuminate\Contracts\View\View;
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

    protected function redirectTrashed(): RedirectResponse
    {
        return redirect()->route('magazine.trash');
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
            new Middleware('permission:magazine-list', only: ['index']),
            new Middleware('permission:magazine-details', only: ['show']),
            new Middleware('permission:magazine-create', only: ['create', 'store']),
            new Middleware('permission:magazine-edit', only: ['edit', 'update']),
            new Middleware('permission:magazine-delete', only: ['destroy']),
            new Middleware('permission:magazine-status', only: ['status']),
            new Middleware('permission:magazine-trash', only: ['trash']),
            new Middleware('permission:magazine-restore', only: ['restore']),
            new Middleware('permission:magazine-permanent-delete', only: ['permanentDelete']),
            //add more permissions if needed
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        if ($request->ajax()) {
            $query = $this->magazineService->getMagazines();
            if ($status) {
                $query = $query->where('status', array_search($status, Magazine::statusList()));
            }
            return DataTables::eloquent($query)
                ->editColumn('status', fn($magazine) => "<span class='badge badge-soft {$magazine->status_color}'>{$magazine->status_label}</span>")
                ->editColumn('created_by', fn($magazine) => $this->creater_name($magazine))
                ->editColumn('created_at', fn($magazine) => $magazine->created_at_formatted)
                ->editColumn('action', fn($magazine) => view('components.admin.action-buttons', [
                    'menuItems' => $this->menuItems($magazine),
                ])->render())
                ->rawColumns(['created_by', 'status', 'created_at', 'action'])
                ->make(true);
        }
        return view('backend.admin.magazine.index');
    }


    protected function menuItems($model): array
    {
        return [
            [
                'routeName' => 'javascript:void(0)',
                'data-id' => encrypt($model->id),
                'className' => 'view',
                'label' => 'Details',
                'permissions' => ['permission-list', 'permission-delete', 'permission-status']
            ],
            [
                'routeName' => 'magazine.edit',
                'params' => [encrypt($model->id)],
                'label' => 'Edit',
                'permissions' => ['permission-edit']
            ],
            [
                'routeName' => 'magazine.status',
                'params' => [encrypt($model->id)],
                'label' => $model->status_btn_label,
                'permissions' => ['permission-status']
            ],
            [
                'routeName' => 'magazine.destroy',
                'params' => [encrypt($model->id)],
                'label' => 'Delete',
                'delete' => true,
                'permissions' => ['permission-delete']
            ]

        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        //
        return view('backend.admin.magazine.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MagazineRequest $request)
    {
        try {
            $validated = $request->validated();
            $file = $request->validated('cover_image') && $request->hasFile('cover_image') ? $request->file('cover_image') : null;
            $this->magazineService->createMagazine($validated, $file);
            session()->flash('success', "Magazine created successfully");
        } catch (\Throwable $e) {
            session()->flash('error', "Magazine creation failed");
            throw $e;
        }
        return $this->redirectIndex();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $data = $this->magazineService->getMagazine($id);
        $data['creater_name'] = $this->creater_name($data);
        $data['updater_name'] = $this->updater_name($data);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $data['magazine'] = $this->magazineService->getMagazine($id);
        return view('backend.admin.magazine.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MagazineRequest $request, string $id)
    {
        try {
            $validated = $request->validated();
            $magazine = $this->magazineService->getMagazine($id);
            $file = $request->validated('cover_image') && $request->hasFile('cover_image') ? $request->file('cover_image') : null;
            $this->magazineService->updateMagazine($magazine, $validated, $file);
            session()->flash('success', "Magazine updated successfully");
        } catch (\Throwable $e) {
            session()->flash('error', "Magazine update failed");
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
            $magazine = $this->magazineService->getMagazine($id);
            $this->magazineService->delete($magazine);
            session()->flash('success', "Magazine deleted successfully");
        } catch (\Throwable $e) {
            session()->flash('error', "Magazine delete failed");
            throw $e;
        }
        return $this->redirectIndex();
    }

    public function trash(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->magazineService->getMagazines()->onlyTrashed();

            return DataTables::eloquent($query)
                ->editColumn('status', fn($magazine) => "<span class='badge badge-soft {$magazine->status_color}'>{$magazine->status_label}</span>")
                ->editColumn('deleted_by', fn($magazine) => $this->deleter_name($magazine))
                ->editColumn('deleted_at', fn($magazine) => $magazine->deleted_at_formatted)
                ->editColumn('action', fn($magazine) => view('components.admin.action-buttons', [
                    'menuItems' => $this->trashedMenuItems($magazine),
                ])->render())
                ->rawColumns(['deleted_by', 'status', 'deleted_at', 'action'])
                ->make(true);
        }
        return view('backend.admin.magazine.trash');
    }


    protected function trashedMenuItems($model): array
    {
        return [
            [
                'routeName' => 'magazine.restore',
                'params' => [encrypt($model->id)],
                'label' => 'Restore',
                'permissions' => ['magazine-restore']
            ],
            [
                'routeName' => 'magazine.permanent-delete',
                'params' => [encrypt($model->id)],
                'label' => 'Permanent Delete',
                'p-delete' => true,
                'permissions' => ['magazine-permanent-delete']
            ]

        ];
    }

    public function restore(string $id): RedirectResponse
    {
        try {
            $this->magazineService->restore($id);
            session()->flash('success', "Magazine restored successfully");
        } catch (\Throwable $e) {
            session()->flash('error', "Magazine restore failed");
            throw $e;
        }
        return $this->redirectTrashed();
    }

    public function permanentDelete(string $id): RedirectResponse
    {
        try {
            $this->magazineService->permanentDelete($id);
            session()->flash('success', "Magazine permanently deleted successfully");
        } catch (\Throwable $e) {
            session()->flash('error', "Magazine permanent delete failed");
            throw $e;
        }
        return $this->redirectTrashed();
    }
    public function status(string $id)
    {
        $magazine = $this->magazineService->getMagazine($id);

        $this->magazineService->toggleStatus($magazine);
        session()->flash('success', 'Magazine status updated successfully!');
        return redirect()->back();
    }
}
