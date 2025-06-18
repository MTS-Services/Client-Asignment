<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NewspaperRequest;
use App\Http\Traits\AuditRelationTraits;
use App\Models\Newspaper;
use App\Services\Admin\NewspaperService;
use Illuminate\Contracts\View\View;
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
        return redirect()->route('newspaper.index');
    }

    protected function redirectTrashed(): RedirectResponse
    {
        return redirect()->route('newspaper.trash');
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
            new Middleware('permission:newspaper-list', only: ['index']),
            new Middleware('permission:newspaper-details', only: ['show']),
            new Middleware('permission:newspaper-create', only: ['create', 'store']),
            new Middleware('permission:newspaper-edit', only: ['edit', 'update']),
            new Middleware('permission:newspaper-delete', only: ['destroy']),
            new Middleware('permission:newspaper-status', only: ['status']),
            new Middleware('permission:newspaper-trash', only: ['trash']),
            new Middleware('permission:newspaper-restore', only: ['restore']),
            new Middleware('permission:newspaper-permanent-delete', only: ['permanentDelete']),
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
            $query = $this->newspaperService->getNewspapers();
            if ($status) {
                $query = $query->where('status', array_search($status, haystack: Newspaper::statusList()));
            }
            return DataTables::eloquent($query)
                ->editColumn('status', fn($newspaper) => "<span class='badge badge-soft {$newspaper->status_color}'>{$newspaper->status_label}</span>")
                ->editColumn('created_by', fn($newspaper) => $this->creater_name($newspaper))
                ->editColumn('created_at', fn($newspaper) => $newspaper->created_at_formatted)
                ->editColumn('action', fn($newspaper) => view('components.admin.action-buttons', [
                    'menuItems' => $this->menuItems($newspaper),
                ])->render())
                ->rawColumns(['created_by', 'status', 'created_at', 'action'])
                ->make(true);
        }
        return view('backend.admin.newspaper.index');
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
                'routeName' => 'newspaper.edit',
                'params' => [encrypt($model->id)],
                'label' => 'Edit',
                'permissions' => ['permission-edit']
            ],
            [
                'routeName' => 'newspaper.status',
                'params' => [encrypt($model->id)],
                'label' => $model->status_btn_label,
                'permissions' => ['permission-status']
            ],
            [
                'routeName' => 'newspaper.destroy',
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
        return view('backend.admin.newspaper.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NewspaperRequest $request)
    {
        try {
            $validated = $request->validated();
            $file = $request->validated('cover_image') && $request->hasFile('cover_image') ? $request->file('cover_image') : null;
            $this->newspaperService->createNewspaper($validated, $file);
            session()->flash('success', "Newspaper created successfully");
        } catch (\Throwable $e) {
            session()->flash('error', "Newspaper creation failed");
            throw $e;
        }
        return $this->redirectIndex();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $data = $this->newspaperService->getNewspaper($id);
        $data['creater_name'] = $this->creater_name($data);
        $data['updater_name'] = $this->updater_name($data);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $data['newspaper'] = $this->newspaperService->getNewspaper($id);
        return view('backend.admin.newspaper.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NewspaperRequest $request, string $id)
    {

        try {
            $validated = $request->validated();
            $newspaper = $this->newspaperService->getNewspaper($id);
            $file = $request->validated('cover_image') && $request->hasFile('cover_image') ? $request->file('cover_image') : null;
            $this->newspaperService->updateNewspaper($newspaper, $validated, $file);
            session()->flash('success', "Newspaper updated successfully");
        } catch (\Throwable $e) {
            session()->flash('error', "Newspaper update failed");
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
            $newspaper = $this->newspaperService->getNewspaper($id);
            $this->newspaperService->delete($newspaper);
            session()->flash('success', "Newspaper deleted successfully");
        } catch (\Throwable $e) {
            session()->flash('error', "Newspaper delete failed");
            throw $e;
        }
        return $this->redirectIndex();
    }

    public function trash(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->newspaperService->getNewspapers()->onlyTrashed();

            return DataTables::eloquent($query)
                ->editColumn('status', fn($newspaper) => "<span class='badge badge-soft {$newspaper->status_color}'>{$newspaper->status_label}</span>")
                ->editColumn('deleted_by', fn($newspaper) => $this->deleter_name($newspaper))
                ->editColumn('deleted_at', fn($newspaper) => $newspaper->deleted_at_formatted)
                ->editColumn('action', fn($newspaper) => view('components.admin.action-buttons', [
                    'menuItems' => $this->trashedMenuItems($newspaper),
                ])->render())
                ->rawColumns(['deleted_by', 'status', 'deleted_at', 'action'])
                ->make(true);
        }
        return view('backend.admin.newspaper.trash');
    }

    protected function trashedMenuItems($model): array
    {
        return [
            [
                'routeName' => 'newspaper.restore',
                'params' => [encrypt($model->id)],
                'label' => 'Restore',
                'permissions' => ['newspaper-restore']
            ],
            [
                'routeName' => 'newspaper.permanent-delete',
                'params' => [encrypt($model->id)],
                'label' => 'Permanent Delete',
                'p-delete' => true,
                'permissions' => ['newspaper-permanent-delete']
            ]

        ];
    }

    public function restore(string $id): RedirectResponse
    {
        try {
            $this->newspaperService->restore($id);
            session()->flash('success', "Newspaper restored successfully");
        } catch (\Throwable $e) {
            session()->flash('error', "Newspaper restore failed");
            throw $e;
        }
        return $this->redirectTrashed();
    }

    public function permanentDelete(string $id): RedirectResponse
    {
        try {
            $this->newspaperService->permanentDelete($id);
            session()->flash('success', "Newspaper permanently deleted successfully");
        } catch (\Throwable $e) {
            session()->flash('error', "Newspaper permanent delete failed");
            throw $e;
        }
        return $this->redirectTrashed();
    }
    public function status(string $id)
    {
        $newspaper = $this->newspaperService->getNewspaper($id);

        $this->newspaperService->toggleStatus($newspaper);
        session()->flash('success', 'Newspaper status updated successfully!');
        return redirect()->back();
    }
}
