<?php

namespace App\Http\Controllers\Backend\User\IssuesManagement;

use App\Http\Controllers\Controller;
use App\Http\Traits\AuditRelationTraits;
use App\Models\BookIssues;
use App\Services\Admin\IssuesManagement\BookIssuesService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Yajra\DataTables\Facades\DataTables;

class BookIssuesController extends Controller implements HasMiddleware
{
    use AuditRelationTraits;

    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('user.book-issues-list');
    }

    protected BookIssuesService $bookIssuesService;

    public function __construct(BookIssuesService $bookIssuesService)
    {
        $this->bookIssuesService = $bookIssuesService;
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
                'params' => [encrypt($model->id), 'status' => $status],
                'className' => 'view',
                'label' => 'Details',
                'permissions' => ['permission-list']
            ],
        ];
    }
    public function issuesShow(Request $request, string $id)
    {
        $book_issue = $this->bookIssuesService->getBookIssues($id);
        return view('backend.user.book-issues.show', compact('book_issue'));
    }
}
