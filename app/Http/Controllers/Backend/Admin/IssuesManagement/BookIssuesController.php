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
                ->editColumn('creater_id', fn($bookIssues) => $this->creater_name($bookIssues))
                ->editColumn('created_at', fn($bookIssues) => $bookIssues->created_at_formatted)
                ->editColumn('action', fn($bookIssues) => view('components.admin.action-buttons', [
                    'menuItems' => $this->menuItems($bookIssues)
                ])->render())
                ->rawColumns(['created_by', 'issued_by', 'returned_by', 'user_id', 'book_id', 'status', 'creater_id', 'action'])
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
                'permissions' => ['book-issues-list', 'book-issues-delete', 'book-issues-status']
            ],
            [
                'routeName' => 'im.book-issues.edit',
                'params' => [encrypt($model->id)],
                'label' => 'Edit',
                'permissions' => ['book-issues-edit']
            ],
            [
                'routeName' => 'im.book-issues.status',
                'params' => [encrypt($model->id)],
                'label' => $model->status_label,
                'permissions' => ['book-issues-status']
            ],
            [
                'routeName' => 'im.book-issues.return',
                'params' => [encrypt($model->id)],
                'label' => 'Return',
                'permissions' => ['book-issues-restore']
            ],
            [
                'routeName' => 'im.book-issues.destroy',
                'params' => [encrypt($model->id)],
                'label' => 'Delete',
                'delete' => true,
                'permissions' => ['book-issues-delete']
            ]

        ];
    }

    public function return($id)
    {
        $data['issue'] = BookIssues::findOrFail(decrypt($id));
        return view('backend.admin.issues-management.book-issues.returned', $data);
    }


    public function updateReturn(Request $request, string $id): RedirectResponse
    {

        try {
            $validated = $request->validate([
                'returned_by' => 'required|exists:users,id',
            ]);
            $this->bookIssuesService->updateReturnBookIssue($id, $validated);
            session()->flash('success', "Book return updated successfully");
        } catch (\Throwable $e) {
            session()->flash('Book return update failed');
            throw $e;
        }
        return $this->redirectIndex();
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
            session()->flash('Book Issues creation failed');
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
    public function edit(string $id): View
    {
        $data['issue'] = $this->bookIssuesService->getBookIssues($id);
        $data['users'] = $this->userService->getUsers()->select(['id', 'name'])->get();
        $data['issueds'] = $this->adminService->getAdmins()->select(['id', 'name'])->get();
        $data['books'] = $this->bookService->getBooks()->select(['id', 'title'])->get();
        return view('backend.admin.issues-management.book-issues.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookIssuesRequest $request, string $id)
    {
        try {
            $validated = $request->validated();
            $issue = $this->bookIssuesService->getBookIssues($id);
            $this->bookIssuesService->updateBookIssues($issue, $validated);
            session()->flash('success', "Book Issues updated successfully");
        } catch (\Throwable $e) {
            session()->flash('Book Issues update failed');
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
            $book = $this->bookIssuesService->getBookIssues($id);
            $this->bookIssuesService->delete($book);
            session()->flash('success', "Book Issues deleted successfully");
        } catch (\Throwable $e) {
            session()->flash('Book Issues delete failed');
            throw $e;
        }
        return $this->redirectIndex();
    }

    public function trash(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->bookIssuesService->getBookIssuess()->onlyTrashed();
            return DataTables::eloquent($query)
                ->editColumn('user_id', fn($bookIssues) => $bookIssues->user?->name)
                ->editColumn('book_id', fn($bookIssues) => $bookIssues->book?->title)
                ->editColumn('issued_by', fn($bookIssues) => $bookIssues->issuedBy?->name)
                ->editColumn('returned_by', fn($bookIssues) => $bookIssues->returnedBy?->name)
                ->editColumn('status', fn($bookIssues) => "<span class='badge badge-soft {$bookIssues->status_color}'>{$bookIssues->status_label}</span>")
                ->editColumn('deleted_by', fn($bookIssues) => $this->deleter_name($bookIssues))
                ->editColumn('deleted_at', fn($bookIssues) => $bookIssues->deleted_at_formatted)
                ->editColumn('action', fn($bookIssues) => view('components.admin.action-buttons', [
                    'menuItems' => $this->trashedMenuItems($bookIssues),
                ])->render())
                ->rawColumns(['created_by', 'issued_by', 'returned_by', 'user_id', 'book_id', 'status', 'deleter_id', 'action'])
                ->make(true);
        }
        return view('backend.admin.issues-management.book-issues.trash');
    }

    protected function trashedMenuItems($model): array
    {
        return [
            [
                'routeName' => 'im.book-issues.restore',
                'params' => [encrypt($model->id)],
                'label' => 'Restore',
                'permissions' => ['book-issues-restore']
            ],
            [
                'routeName' => 'im.book-issues.permanent-delete',
                'params' => [encrypt($model->id)],
                'label' => 'Permanent Delete',
                'p-delete' => true,
                'permissions' => ['book-issues-permanent-delete']
            ]

        ];
    }

    public function restore(string $id): RedirectResponse
    {
        try {
            $this->bookIssuesService->restore($id);
            session()->flash('success', "Book Issues restored successfully");
        } catch (\Throwable $e) {
            session()->flash('Book Issues restore failed');
            throw $e;
        }
        return $this->redirectTrashed();
    }

    public function permanentDelete(string $id): RedirectResponse
    {

        try {
            $this->bookIssuesService->permanentDelete($id);
            session()->flash('success', "Book Issues permanently deleted successfully");
        } catch (\Throwable $e) {
            session()->flash('Book Issues permanent delete failed');
            throw $e;
        }
        return $this->redirectTrashed();
    }
      public function status(string $id)
    {
        $bookIssues = $this->bookIssuesService->getBookIssues($id);
        if ($bookIssues->role_id == 1 && admin()->role_id != 1) {
            session()->flash('error', 'Only a Super Admin can change status of another Super Admin!');
            return redirect()->back();
        }
        $this->bookIssuesService->toggleStatus($bookIssues);
        session()->flash('success', 'Admin status updated successfully!');
        return redirect()->back();
    }
}
