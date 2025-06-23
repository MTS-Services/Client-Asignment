<?php

namespace App\Http\Controllers\Backend\Admin\IssuesManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\IssuesManagement\BookIssuesRequest;
use App\Http\Requests\Admin\IssuesManagement\BookLostReuest;
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
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class BookIssuesController extends Controller implements HasMiddleware
{
    use AuditRelationTraits;

    protected function redirectIndex($status): RedirectResponse
    {
        return redirect()->route('bim.book-issues.index', ['status' => $status]);
    }

    protected function redirectTrashed($status): RedirectResponse
    {
        return redirect()->route('bim.book-issues.trash', ['status' => $status]);
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
            new Middleware('permission:book-issues-list-pending', only: ['index']),
            new Middleware('permission:book-issues-list-issued', only: ['index']),
            new Middleware('permission:book-issues-list-returned', only: ['index']),
            new Middleware('permission:book-issues-list-overdue', only: ['index']),
            new Middleware('permission:book-issues-list-lost', only: ['index']),
            new Middleware('permission:book-issues-list-unpaid', only: ['index']),
            new Middleware('permission:book-issues-list-paid', only: ['index']),

            new Middleware('permission:book-issues-details', only: ['show']),
            new Middleware('permission:book-issues-create', only: ['create', 'store']),
            new Middleware('permission:book-issues-edit', only: ['edit', 'update']),
            new Middleware('permission:book-issues-delete', only: ['destroy']),
            new Middleware('permission:book-issues-status', only: ['status']),
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
        $status = $request->get('status');

        $fine_status = $request->get('fine-status') ?? null;

        if ($request->ajax()) {
            $query = $this->bookIssuesService->getBookIssuess();
            if ($status) {
                $query = $query->where('status', array_search($status, BookIssues::statusList()));
            }
            if ($fine_status) {
                $query = $query->where('fine_status', array_search($fine_status, BookIssues::fineStatusList()));
            }

            return DataTables::eloquent($query)
                ->editColumn('user_id', fn($bookIssues) => $bookIssues->user?->name)
                ->editColumn('book_id', fn($bookIssues) => $bookIssues->book?->title)
                ->editColumn('issue_date', fn($bookIssues) => dateFormat($bookIssues->issue_date))
                ->editColumn('status', fn($bookIssues) => "<span class='badge badge-soft {$bookIssues->status_color}'>{$bookIssues->status_label}</span>")
                ->editColumn('creater_id', fn($bookIssues) => $this->creater_name($bookIssues))
                ->editColumn('created_at', fn($bookIssues) => $bookIssues->created_at_formatted)
                ->editColumn('action', fn($bookIssues) => view('components.admin.action-buttons', [
                    'menuItems' => $this->menuItems($bookIssues, $status, $fine_status)
                ])->render())
                ->rawColumns(['created_by', 'issue_date', 'user_id', 'book_id', 'status', 'creater_id', 'action'])
                ->make(true);
        }

        return view('backend.admin.issues-management.book-issues.index', compact('status'));
    }


    protected function menuItems($model, $status, $fine_status = null): array
    {
        $items = [
            [
                'routeName' => 'javascript:void(0)',
                'data-id' => encrypt($model->id),
                'className' => 'view',
                'label' => 'Details',
                'permissions' => ['book-issues-list', 'book-issues-delete', 'book-issues-status']
            ],
        ];

        if ($model->fine_status == BookIssues::FINE_UNPAID) {
            $items = array_merge($items, [
                [
                    'routeName' => 'bm.book-issues.fine-status',
                    'params' => [
                        encrypt($model->id),
                        'status' => BookIssues::fineStatusList()[BookIssues::FINE_PAID]
                    ],
                    'label' => 'Make Paid',
                    'permissions' => ['book-issues-edit']
                ],
            ]);
        }

        if ($model->status == BookIssues::STATUS_PENDING) {
            $items = array_merge($items, [
                [
                    'routeName' => 'bim.book-issues.edit',
                    'params' => [encrypt($model->id), 'status' => $status],
                    'label' => 'Edit',
                    'permissions' => ['book-issues-edit']
                ],
                [
                    'routeName' => 'bim.book-issues.status',
                    'params' => [
                        encrypt($model->id),
                        'status' => BookIssues::statusList()[BookIssues::STATUS_ISSUED]
                    ],
                    'label' => 'Issue',
                    'issue' => true,
                    'permissions' => ['book-issues-status']
                ],
                [
                    'routeName' => 'bim.book-issues.destroy',
                    'params' => [encrypt($model->id), 'status' => $status],
                    'label' => 'Delete',
                    'delete' => true,
                    'permissions' => ['book-issues-delete']
                ]
            ]);
        }
        if ($model->status == BookIssues::STATUS_ISSUED) {
            $items = array_merge($items, [
                [
                    'routeName' => 'bim.book-issues.status',
                    'params' => [
                        encrypt($model->id),
                        'status' => BookIssues::statusList()[BookIssues::STATUS_PENDING]
                    ],
                    'label' => 'Cancel Issue',
                    'permissions' => ['book-issues-status']
                ],
                [
                    'routeName' => 'bim.book-issues.return',
                    'params' => [encrypt($model->id), 'status' => $status],
                    'label' => 'Return',
                    'permissions' => ['book-issues-return']
                ],
                [
                    'routeName' => 'bim.book-issues.lost',
                    'params' => [encrypt($model->id), 'status' => $status],
                    'label' => 'Lost',
                    'permissions' => ['book-issues-lost']
                ],
            ]);
        }
        return $items;
    }

    // Book return Issues

    public function return($id)
    {
        $data['issue'] = BookIssues::findOrFail(decrypt($id));
        return view('backend.admin.issues-management.book-issues.returned', $data);
    }

    public function updateReturn(BookIssuesRequest $request, string $id): RedirectResponse
    {

        try {
            $validated = $request->validated();
            // Update book issue
            $this->bookIssuesService->updateReturnBookIssue($id, $validated);

            session()->flash('success', "Book return updated successfully");
        } catch (\Throwable $e) {
            session()->flash('error', 'Book return update failed');
            throw $e;
        }
        return $this->redirectIndex(request('status'));
    }

    // Book lost Issues
    public function lost($id)
    {
        $data['issue_lost'] = BookIssues::findOrFail(decrypt($id));
        return view('backend.admin.issues-management.book-issues.lost', $data);
    }
    public function updateLost(BookLostReuest $request, string $id): RedirectResponse
    {

        try {
            $validated = $request->validated();
            // Update book issue
            $this->bookIssuesService->updateBookLost($id, $validated);

            session()->flash('success', "Book Lost Information updated successfully");
        } catch (\Throwable $e) {
            session()->flash('error', 'Book Lost Information update failed');
            throw $e;
        }
        return $this->redirectIndex(request('status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $data['users'] = $this->userService->getUsers()->active()->select(['id', 'name'])->get();
        $data['books'] = $this->bookService->getBooks()->available()->select(['id', 'title'])->get();
        return view('backend.admin.issues-management.book-issues.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookIssuesRequest $request)
    {

        try {
            $validated = $request->validated();
            $validated['status'] = BookIssues::STATUS_ISSUED;
            $validated['issue_code'] = generateBookIssueNumber();
            $validated['issued_by'] = admin()->id;
            $validated['creater_id'] = admin()->id;
            $validated['creater_type'] = get_class(admin());
            $this->bookIssuesService->createBookIssues($validated);
            session()->flash('success', "Book issues created successfully");
        } catch (\Throwable $e) {
            session()->flash('Book Issues creation failed');
            throw $e;
        }
        return $this->redirectIndex(BookIssues::statusList()[BookIssues::STATUS_ISSUED]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $data = $this->bookIssuesService->getBookIssues($id);
        $data['username'] = $data->user?->name;
        $data['bookTitle'] = $data->book?->title;
        $data['issuedBy'] = $data->issuedBy?->name;
        $data['returnedBy'] = $data->returnedBy?->name;
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
        $data['users'] = $this->userService->getUsers()->active()->select(['id', 'name'])->get();
        $data['books'] = $this->bookService->getBooks()->available()->select(['id', 'title'])->get();
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
        return $this->redirectIndex(request('status'));
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
        return $this->redirectIndex(request('status'));
    }

    public function trash(Request $request)
    {
        $status = request('status');
        if ($request->ajax()) {
            $query = $this->bookIssuesService->getBookIssuess()->onlyTrashed();
            return DataTables::eloquent($query)
                ->editColumn('user_id', fn($bookIssues) => $bookIssues->user?->name)
                ->editColumn('book_id', fn($bookIssues) => $bookIssues->book?->title)
                ->editColumn('issue_date', fn($bookIssues) => dateFormat($bookIssues->issue_date))
                ->editColumn('status', fn($bookIssues) => "<span class='badge badge-soft {$bookIssues->status_color}'>{$bookIssues->status_label}</span>")
                ->editColumn('deleted_by', fn($bookIssues) => $this->deleter_name($bookIssues))
                ->editColumn('deleted_at', fn($bookIssues) => $bookIssues->deleted_at_formatted)
                ->editColumn('action', fn($bookIssues) => view('components.admin.action-buttons', [
                    'menuItems' => $this->trashedMenuItems($bookIssues, $status),
                ])->render())
                ->rawColumns(['created_by', 'issue_date', 'user_id', 'book_id', 'status', 'deleter_id', 'action'])
                ->make(true);
        }
        return view('backend.admin.issues-management.book-issues.trash');
    }

    protected function trashedMenuItems($model, $status): array
    {
        return [
            [
                'routeName' => 'bim.book-issues.restore',
                'params' => [encrypt($model->id), 'status' => $status],
                'label' => 'Restore',
                'permissions' => ['book-issues-restore']
            ],
            [
                'routeName' => 'bim.book-issues.permanent-delete',
                'params' => [encrypt($model->id), 'status' => $status],
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
        return $this->redirectTrashed(request('status'));
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
        return $this->redirectTrashed(request('status'));
    }
    public function status(string $id, string $status)
    {
        $bookIssues = $this->bookIssuesService->getBookIssues($id);
        match ($status) {
            BookIssues::statusList()[BookIssues::STATUS_ISSUED] => $bookIssues->update(['status' => BookIssues::STATUS_ISSUED, 'issued_by' => admin()->id, 'updater_id' => admin()->id, 'updater_type' => get_class(admin())]),
            BookIssues::statusList()[BookIssues::STATUS_PENDING] => $bookIssues->update(['status' => BookIssues::STATUS_PENDING, 'updater_id' => admin()->id, 'updater_type' => get_class(admin())]),
        };
        session()->flash('success', 'Admin status updated successfully!');
        return redirect()->back();
    }

    public function fineStatus(string $id, string $status)
    {
        try {
            $this->bookIssuesService->updateFineStatus($id, $status);
            session()->flash('success', 'Fine status updated successfully!');
        } catch (Throwable $e) {
            session()->flash('Something went wrong! Please try again.');
            throw $e;
        }
        return redirect()->back()->with('fine-status', BookIssues::fineStatusList()[BookIssues::FINE_UNPAID]);
    }
}
