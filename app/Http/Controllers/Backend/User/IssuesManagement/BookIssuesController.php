<?php

namespace App\Http\Controllers\Backend\User\IssuesManagement;

use App\Http\Controllers\Controller;
use App\Http\Traits\AuditRelationTraits;
use App\Models\BookIssues;
use App\Services\Admin\AdminManagement\AdminService;
use App\Services\Admin\BookService;
use App\Services\Admin\IssuesManagement\BookIssuesService;
use App\Services\Admin\UserManagement\UserService;
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
        return redirect()->route('user.book-issues-list');
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
            'auth:web',
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function issuesList(Request $request)
    {
        $status = $request->get('status');
        if ($request->ajax()) {
            $query = $this->bookIssuesService->getBookIssuess()->where('status', array_search($status, BookIssues::statusList()))->self();

            return DataTables::eloquent($query)
                ->editColumn('user_id', fn($bookIssues) => $bookIssues->user?->name)
                ->editColumn('book_id', fn($bookIssues) => $bookIssues->book?->title)
                ->editColumn('status', fn($bookIssues) => "<span class='badge badge-soft {$bookIssues->status_color}'>{$bookIssues->status_label}</span>")
                ->editColumn('created_at', fn($bookIssues) => $bookIssues->created_at_formatted)
                ->editColumn('action', fn($bookIssues) => view('components.user.action-buttons', [
                    'menuItems' => $this->menuItems($bookIssues, $status, $request)
                ])->render())
                ->rawColumns(['created_by', 'issued_by', 'returned_by', 'user_id', 'book_id', 'status', 'creater_id', 'action'])
                ->make(true);
        }

        return view('backend.user.book-issues.index', compact('status'));
    }


    protected function menuItems($model, $status): array
    {
        return [
            [
                'routeName' => 'user.book-issues-show',
                'params' => ['status' => $status],
                'className' => 'view',
                'label' => 'Details',
                'permissions' => ['permission-list']
            ],
        ];
    }
    public function issuesShow(Request $request, string $status)
    {
        $book_issue = $this->bookIssuesService->getBookIssues($status, 'status');
        $book_issue['username'] = $book_issue->user?->name;
        $book_issue['bookTitle'] = $book_issue->book?->title;
        $book_issue['issuedBy'] = $book_issue->issuedBy?->name;
        $book_issue['returnedBy'] = $book_issue->returnedBy?->name;
        $book_issue['creater_name'] = $this->creater_name($book_issue);
        $book_issue['updater_name'] = $this->updater_name($book_issue);
        return view('backend.user.book-issues.show', compact('book_issue'));
    }
}
