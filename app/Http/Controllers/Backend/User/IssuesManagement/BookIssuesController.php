<?php

namespace App\Http\Controllers\Backend\User\IssuesManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\BookIssuesRequest;
use App\Http\Traits\AuditRelationTraits;
use App\Models\BookIssues;
use App\Services\Admin\BookService;
use App\Services\Admin\IssuesManagement\BookIssuesService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Yajra\DataTables\Facades\DataTables;


class BookIssuesController extends Controller implements HasMiddleware
{
    use AuditRelationTraits;

    protected function redirectIndex($status): RedirectResponse
    {
        return redirect()->route('user.book-issues-list', ['status' => $status]);
    }

    protected BookIssuesService $bookIssuesService;
    protected BookService $bookService;

    public function __construct(BookIssuesService $bookIssuesService, BookService $bookService)
    {
        $this->bookIssuesService = $bookIssuesService;
        $this->bookService = $bookService;
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
        $fine_status = $request->get('fine-status') ?? null;
        if ($request->ajax()) {
            $query = $this->bookIssuesService->getBookIssuess()->self();
            if ($status) {
                $query->where('status', array_search($status, BookIssues::statusList()));
            }
            if ($fine_status) {
                $query->where('fine_status', array_search($fine_status, BookIssues::fineStatusList()));
            }
            return DataTables::eloquent($query)
                ->editColumn('user_id', fn($bookIssues) => $bookIssues->user?->name)
                ->editColumn('book_id', fn($bookIssues) => $bookIssues->book?->title)
                ->editColumn('status', fn($bookIssues) => "<span class='badge badge-soft {$bookIssues->status_color}'>{$bookIssues->status_label}</span>")
                ->editColumn('created_at', fn($bookIssues) => $bookIssues->created_at_formatted)
                ->editColumn('action', fn($bookIssues) => view('components.user.action-buttons', [
                    'menuItems' => $this->menuItems($bookIssues, $status)
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
                'params' => [$model->issue_code, 'status' => $status],
                'className' => 'view',
                'label' => 'Details',
                'permissions' => ['permission-list']
            ],
        ];
    }
    public function issuesShow(Request $request, $issue_code)
    {
        $book_issue = $this->bookIssuesService->getBookIssues($issue_code, 'issue_code');
        return view('backend.user.book-issues.show', compact('book_issue'));
    }
    public function issuesCreate(Request $request)
    {
        $data['books'] = $this->bookService->getBooks()->available()->select(['id', 'title'])->get();
        return view('backend.user.book-issues.create', $data);
    }
    public function issuesRequest(BookIssuesRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['status'] = BookIssues::STATUS_ISSUED;
            $validated['issue_code'] = generateBookIssueNumber();
            $validated['user_id'] = user()->id;
            $validated['creater_id'] = user()->id;
            $validated['creater_type'] = get_class(user());
            $this->bookIssuesService->createBookIssues($validated);
            session()->flash('success', "Book issues Request successfully");
        } catch (\Throwable $e) {
            session()->flash('Book Issues Request failed');
            throw $e;
        }
        return $this->redirectIndex(BookIssues::statusList()[BookIssues::STATUS_ISSUED]);
    }
}
