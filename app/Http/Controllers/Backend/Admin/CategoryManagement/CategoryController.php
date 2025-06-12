<?php

namespace App\Http\Controllers\Backend\Admin\CategoryManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryManagement\CategoryRequest;
use App\Http\Traits\AuditRelationTraits;
use App\Models\Category;
use App\Services\Admin\CategoryManagement\CategoryService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller implements HasMiddleware
{
    use AuditRelationTraits;

    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('am.category.index');
    }

    protected function redirectTrashed(): RedirectResponse
    {
        return redirect()->route('am.category.trash');
    }

    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    
    public static function middleware(): array
    {
        return [
            'auth:admin', // Applies 'auth:admin' to all methods

            // Permission middlewares using the Middleware class
            new Middleware('permission:permisison-list', only: ['index']),
            new Middleware('permission:permisison-details', only: ['show']),
            new Middleware('permission:permisison-create', only: ['create', 'store']),
            new Middleware('permission:permisison-edit', only: ['edit', 'update']),
            new Middleware('permission:permisison-delete', only: ['destroy']),
            new Middleware('permission:permisison-trash', only: ['trash']),
            new Middleware('permission:permisison-restore', only: ['restore']),
            new Middleware('permission:permisison-permanent-delete', only: ['permanentDelete']),
            //add more permissions if needed
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->categoryService->getCategories();
            return DataTables::eloquent($query)
                ->editColumn('status', function ($category) {
                    return "<span class='badge badge-soft " . $category->status_color . "'>" . $category->status_label . "</span>";
                })
                 ->editColumn('created_by', function ($category) {
                    return $this->creater_name($category);
                })
                ->editColumn('created_at', function ($category) {
                    return $category->created_at_formatted;
                })
                ->editColumn('action', function ($service) {
                    $menuItems = $this->menuItems($service);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['status', 'created_by', 'created_at', 'action'])
                ->make(true);
        }
        return view('backend.admin.category-management.category.index');
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
            // [
            //     'routeName' => 'am.category.status',
            //     'params' => [encrypt($model->id)],
            //     'label' => $model->status_btn_label,
            //     'permissions' => ['permission-status']
            // ],
            [
                'routeName' => 'am.category.edit',
                'params' => [encrypt($model->id)],
                'label' => 'Edit',
                'permissions' => ['permission-edit']
            ],

            [
                'routeName' => 'am.category.destroy',
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
        return view('backend.admin.category-management.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest  $request)
    {
         try {
            $validated = $request->validated();
            $this->categoryService->createCategory($validated);
            session()->flash('success', "Service created successfully");
        } catch (\Throwable $e) {
            session()->flash('Service creation failed');
            throw $e;
        }
        return $this->redirectIndex();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $data = $this->categoryService->getCategory($id);
        $data->load(['parent:id,name,slug,description']);
        $data['creater_name'] = $this->creater_name($data);
        $data['updater_name'] = $this->updater_name($data);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $data['category'] = $this->categoryService->getCategory($id);
        return view('backend.admin.category-management.category.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
         try {
            $validated = $request->validated();
            $this->categoryService->updateCategory($this->categoryService->getCategory($id), $validated);
            session()->flash('success', "Service updated successfully");
        } catch (\Throwable $e) {
            session()->flash('Service update failed');
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
            $this->categoryService->deleteCategory($this->categoryService->getCategory($id));
            session()->flash('success', "Service deleted successfully");
        } catch (\Throwable $e) {
            session()->flash('Service delete failed');
            throw $e;
        }
        return $this->redirectIndex();
    }

    public function trash(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->categoryService->getCategories()->onlyTrashed();
            return DataTables::eloquent($query)
            ->editColumn('status', function ($category) {
                return "<span class='badge badge-soft " . $category->status_color . "'>" . $category->status_label . "</span>";
            })
                ->editColumn('deleted_by', function ($admin) {
                    return $this->deleter_name($admin);
                })
                ->editColumn('deleted_at', function ($admin) {
                    return $admin->deleted_at_formatted;
                })
                ->editColumn('action', function ($permission) {
                    $menuItems = $this->trashedMenuItems($permission);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['status', 'deleted_by', 'deleted_at', 'action'])
                ->make(true);
        }
        return view('backend.admin.category-management.category.trash');
    }

    protected function trashedMenuItems($model): array
    {
        return [
            [
                'routeName' => 'am.category.restore',
                'params' => [encrypt($model->id)],
                'label' => 'Restore',
                'permissions' => ['permission-restore']
            ],
            [
                'routeName' => 'am.category.permanent-delete',
                'params' => [encrypt($model->id)],
                'label' => 'Permanent Delete',
                'p-delete' => true,
                'permissions' => ['permission-permanent-delete']
            ]

        ];
    }

     public function restore(string $id)
    {
        try {
            $this->categoryService->restore(encrypt($id));
            session()->flash('success', "Service restored successfully");
        } catch (\Throwable $e) {
            session()->flash('Service restore failed');
            throw $e;
        }
        return $this->redirectTrashed();
    }

    public function permanentDelete(string $id): RedirectResponse
    {
        try {
            $this->categoryService->permanentDelete($this->categoryService->getCategory($id));
            session()->flash('success', "Service permanently deleted successfully");
        } catch (\Throwable $e) {
            session()->flash('Service permanent delete failed');
            throw $e;
        }
        return $this->redirectTrashed();
    }
}
