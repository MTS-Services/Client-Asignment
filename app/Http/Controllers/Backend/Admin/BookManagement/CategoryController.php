<?php

namespace App\Http\Controllers\Backend\Admin\BookManagement;

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
        return redirect()->route('bm.category.index');
    }

    protected function redirectTrashed(): RedirectResponse
    {
        return redirect()->route('bm.category.trash');
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
            new Middleware('permission:category-list', only: ['index']),
            new Middleware('permission:category-details', only: ['show']),
            new Middleware('permission:category-create', only: ['create', 'store']),
            new Middleware('permission:category-edit', only: ['edit', 'update']),
            new Middleware('permission:category-delete', only: ['destroy']),
            new Middleware('permission:category-status', only: ['status']),
            new Middleware('permission:category-trash', only: ['trash']),
            new Middleware('permission:category-restore', only: ['restore']),
            new Middleware('permission:category-permanent-delete', only: ['permanentDelete']),
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
            $query = $this->categoryService->getCategories();
            if ($status) {
                $query = $query->where('status', array_search($status, Category::statusList()));
            }
            return DataTables::eloquent($query)
                ->editColumn('status', fn($category) => "<span class='badge badge-soft {$category->status_color}'>{$category->status_label}</span>")
                ->editColumn('created_by', fn($category) => $this->creater_name($category))
                ->editColumn('created_at', fn($category) => $category->created_at_formatted)
                ->editColumn('action', fn($category) => view('components.admin.action-buttons', [
                    'menuItems' => $this->menuItems($category),
                ])->render())
                ->rawColumns(['status', 'created_by', 'created_at', 'action'])
                ->make(true);
        }

        return view('backend.admin.book-management.category.index');
    }

    protected function menuItems($model): array
    {
        return [
            [
                'routeName' => 'javascript:void(0)',
                'data-id' => encrypt($model->id),
                'className' => 'view',
                'label' => 'Details',
                'permissions' => ['category-list', 'category-delete', 'category-status']
            ],
            [
                'routeName' => 'bm.category.edit',
                'params' => [encrypt($model->id)],
                'label' => 'Edit',
                'permissions' => ['category-edit']
            ],
            [
                'routeName' => 'bm.category.status',
                'params' => [encrypt($model->id)],
                'label' => $model->status_btn_label,
                'permissions' => ['category-status']
            ],
            [
                'routeName' => 'bm.category.destroy',
                'params' => [encrypt($model->id)],
                'label' => 'Delete',
                'delete' => true,
                'permissions' => ['category-delete']
            ]

        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        //
        return view('backend.admin.book-management.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
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
        return view('backend.admin.book-management.category.edit', $data);
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
                ->editColumn('status', fn($category) => "<span class='badge badge-soft {$category->status_color}'>{$category->status_label}</span>")
                ->editColumn('deleted_by', fn($category) => $this->deleter_name($category))
                ->editColumn('deleted_at', fn($category) => $category->deleted_at_formatted)
                ->editColumn('action', fn($category) => view('components.admin.action-buttons', [
                    'menuItems' => $this->trashedMenuItems($category),
                ])->render())
                ->rawColumns(['status', 'deleted_by', 'deleted_at', 'action'])
                ->make(true);
        }

        return view('backend.admin.book-management.category.trash');
    }


    protected function trashedMenuItems($model): array
    {
        return [
            [
                'routeName' => 'bm.category.restore',
                'params' => [encrypt($model->id)],
                'label' => 'Restore',
                'permissions' => ['category-restore']
            ],
            [
                'routeName' => 'bm.category.permanent-delete',
                'params' => [encrypt($model->id)],
                'label' => 'Permanent Delete',
                'p-delete' => true,
                'permissions' => ['category-permanent-delete']
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
    public function status(string $id)
    {
        $magazine = $this->categoryService->getCategory($id);

        $this->categoryService->toggleStatus($magazine);
        session()->flash('success', 'Category status updated successfully!');
        return redirect()->back();
    }
}
