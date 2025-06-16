<?php

namespace App\Http\Controllers\Backend\Admin\IssuesManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\IssuesManagement\BookIssuesRequest;
use App\Http\Traits\AuditRelationTraits;
use App\Models\Book;
use App\Models\BookIssues;
use App\Models\User;
use App\Services\Admin\AdminManagement\AdminService;
use App\Services\Admin\BookService;
use App\Services\Admin\IssuesManagement\BookIssuesService;
use App\Services\Admin\UserManagement\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;

class BookIssuesController extends Controller implements HasMiddleware
{
    use AuditRelationTraits;

    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('im.book-issues.index');
    }

    protected function redirectTrashed(): RedirectResponse
    {
        return redirect()->route('im.book-issues.trash');
    }

    protected BookIssuesService $bookIssuesService;
    protected UserService $userService;
    protected BookService $bookService;
    protected AdminService $adminService;


    public function __construct(BookIssuesService $bookIssuesService, UserService $userService, BookService $bookService, AdminService $adminService)
    {
        $this->bookIssuesService = $bookIssuesService;
        $this->userService = $userService;
        $this->bookService = $bookService;
        $this->adminService = $adminService;
    }

    public static function middleware(): array
    {
        return [
            'auth:admin', // Applies 'auth:admin' to all methods

            // Permission middlewares using the Middleware class
            new Middleware('permission:book-issues-list', only: ['index']),
            new Middleware('permission:book-issues-details', only: ['show']),
            new Middleware('permission:book-issues-create', only: ['create', 'store']),
            new Middleware('permission:book-issues-edit', only: ['edit', 'update']),
            new Middleware('permission:book-issues-delete', only: ['destroy']),
            new Middleware('permission:book-issues-trash', only: ['trash']),
            new Middleware('permission:book-issues-restore', only: ['restore']),
            new Middleware('permission:book-issues-permanent-delete', only: ['permanentDelete']),
            //add more permissions if needed
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->bookIssuesService->getBookIssuess();

            return DataTables::eloquent($query)
                ->editColumn('user_id', fn($bookIssues) => $bookIssues->user?->name)
                ->editColumn('book_id', fn($bookIssues) => $bookIssues->book?->title)
                ->editColumn('issued_by', fn($bookIssues) => $bookIssues->issuedBy?->name)
                ->editColumn('returned_by', fn($bookIssues) => $bookIssues->returnedBy?->name)
                ->editColumn('status', fn($bookIssues) => "<span class='badge badge-soft {$bookIssues->status_color}'>{$bookIssues->status_label}</span>")
                ->editColumn('created_by', fn($bookIssues) => $this->creater_name($bookIssues))
                ->editColumn('created_at', fn($bookIssues) => $bookIssues->created_at_formatted)
                ->editColumn('action', fn($bookIssues) => view('components.admin.action-buttons', [
                    'menuItems' => $this->menuItems($bookIssues)
                ])->render())
                ->rawColumns(['created_by', 'issued_by', 'returned_by', 'user_id', 'book_id', 'status', 'created_at', 'action'])
                ->make(true);
        }

        return view('backend.admin.issues-management.book-issues.index');
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
                'routeName' => 'im.book-issues.edit',
                'params' => [encrypt($model->id)],
                'label' => 'Edit',
                'permissions' => ['permission-edit']
            ],
            [
                'routeName' => 'im.book-issues.status',
                'params' => [encrypt($model->id)],
                'label' => $model->status_label,
                'permissions' => ['permission-status']
            ],
            [
                'routeName' => 'im.book-issues.return',
                'params' => [encrypt($model->id)],
                'label' => 'Return',
                'permissions' => ['permission-restore']
            ],
            [
                'routeName' => 'im.book-issues.destroy',
                'params' => [encrypt($model->id)],
                'label' => 'Delete',
                'delete' => true,
                'permissions' => ['permission-delete']
            ]

        ];
    }

    public function return($id)
    {
        $data['issue'] = BookIssues::findOrFail($id);
        return view('backend.admin.issues-management.book-issues.returned', $data);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $data['users'] = $this->userService->getUsers()->select(['id', 'name'])->get();
        $data['issueds'] = $this->adminService->getAdmins()->select(['id', 'name'])->get();
        $data['books'] = $this->bookService->getBooks()->select(['id', 'title'])->get();
        return view('backend.admin.issues-management.book-issues.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookIssuesRequest $request)
    {
        try {
            $validated = $request->validated();
            $this->bookIssuesService->createBookIssues($validated);
            session()->flash('success', "Book issues created successfully");
        } catch (\Throwable $e) {
            session()->flash('Book issues creation failed');
            throw $e;
        }
        return $this->redirectIndex();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $data = $this->bookIssuesService->getBookIssues($id);
        $data['creater_name'] = $this->creater_name($data);
        $data['updater_name'] = $this->updater_name($data);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(string $id): View
    // {
    //     //$data['service'] = $this->bookIssuesService->getService($id);
    //     return view('view file url...', $data);
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // $validated = $request->validated();
            //
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
            //
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
            $query = $this->bookIssuesService->getBookIssuess()->onlyTrashed();
            return DataTables::eloquent($query)
                ->editColumn('deleted_by', function ($bookIssues) {
                    return $this->deleter_name($bookIssues);
                })
                ->editColumn('deleted_at', function ($bookIssues) {
                    return $bookIssues->deleted_at_formatted;
                })
                ->editColumn('action', function ($permission) {
                    $menuItems = $this->trashedMenuItems($permission);
                    return view('components.admin.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['deleted_by', 'deleted_at', 'action'])
                ->make(true);
        }
        return view('view blade file url...');
    }

    protected function trashedMenuItems($model): array
    {
        return [
            [
                'routeName' => '',
                'params' => [encrypt($model->id)],
                'label' => 'Restore',
                'permissions' => ['permission-restore']
            ],
            [
                'routeName' => '',
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
            $this->bookIssuesService->restore($id);
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
            $this->bookIssuesService->permanentDelete($id);
            session()->flash('success', "Service permanently deleted successfully");
        } catch (\Throwable $e) {
            session()->flash('Service permanent delete failed');
            throw $e;
        }
        return $this->redirectTrashed();
    }

    public function updateReturn(Request $request, string $id): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'return_date' => 'required|date',
                'returned_by' => 'required|exists:users,id',
            ]);
            $validated['status'] = BookIssues::STATUS_RETURNED;
            $issue = BookIssues::findOrFail(decrypt($id));
            $issue->update($validated);
            session()->flash('success', "Book return updated successfully");
        } catch (\Throwable $e) {
            session()->flash('Book return update failed');
            throw $e;
        }
        return $this->redirectIndex();
    }
}
