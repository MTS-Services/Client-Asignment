<?php

namespace App\Http\Controllers\Backend\Admin\BookManagement;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Services\Admin\BookService;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BookRequest;
use Illuminate\Http\RedirectResponse;
use App\Http\Traits\AuditRelationTraits;
use App\Services\Admin\CategoryManagement\CategoryService;
use App\Services\Admin\PublishManagement\PublisherService;
use App\Services\Admin\RackService;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class BookController extends Controller implements HasMiddleware
{
    use AuditRelationTraits;

    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('bm.book.index');
    }

    protected function redirectTrashed(): RedirectResponse
    {
        return redirect()->route('bm.book.trash');
    }

    protected BookService $bookService;
    protected CategoryService $categoryService;
    protected PublisherService $publisherService;
    protected RackService $rackService;

    public function __construct(BookService $bookService, CategoryService $categoryService, PublisherService $publisherService, RackService $rackService)
    {
        $this->bookService = $bookService;
        $this->categoryService = $categoryService;
        $this->publisherService = $publisherService;
        $this->rackService = $rackService;
    }

    public static function middleware(): array
    {
        return [
            'auth:admin', // Applies 'auth:admin' to all methods

            // Permission middlewares using the Middleware class
            new Middleware('permission:book-list', only: ['index']),
            new Middleware('permission:book-details', only: ['show']),
            new Middleware('permission:book-create', only: ['create', 'store']),
            new Middleware('permission:book-edit', only: ['edit', 'update']),
            new Middleware('permission:book-status', only: ['status']),
            new Middleware('permission:book-delete', only: ['destroy']),
            new Middleware('permission:book-trash', only: ['trash']),
            new Middleware('permission:book-restore', only: ['restore']),
            new Middleware('permission:book-permanent-delete', only: ['permanentDelete']),
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
            $query = $this->bookService->getBooks();
            if ($status) {
                $query = $query->where('status', array_search($status, Book::statusList()));
            }
            return DataTables::eloquent($query)
                ->editColumn('category_id', fn($book) => $book->category?->name)
                ->editColumn('publisher_id', fn($book) => $book->publisher?->name)
                ->editColumn('rack_id', fn($book) => $book->rack?->rack_number)
                ->editColumn('status', fn($book) => "<span class='badge badge-soft {$book->status_color}'>{$book->status_label}</span>")
                ->editColumn('created_by', fn($book) => $this->creater_name($book))
                ->editColumn('created_at', fn($book) => $book->created_at_formatted)
                ->editColumn('action', fn($book) => view('components.admin.action-buttons', [
                    'menuItems' => $this->menuItems($book),
                ])->render())
                ->rawColumns(['created_by', 'status', 'rack_id', 'publisher_id', 'category_id', 'created_at', 'action'])
                ->make(true);
        }

        return view('backend.admin.book-management.book.index');
    }


    protected function menuItems($model): array
    {
        return [
            [
                'routeName' => 'javascript:void(0)',
                'data-id' => encrypt($model->id),
                'className' => 'view',
                'label' => 'Details',
                'permissions' => ['book-list', 'book-delete', 'book-status']
            ],
            [
                'routeName' => 'bm.book.edit',
                'params' => [encrypt($model->id)],
                'label' => 'Edit',
                'permissions' => ['book-edit']
            ],
            [
                'routeName' => 'bm.book.status',
                'params' => [encrypt($model->id)],
                'label' => $model->status_btn_label,
                'permissions' => ['book-status']
            ],
            [
                'routeName' => 'bm.book.destroy',
                'params' => [encrypt($model->id)],
                'label' => 'Delete',
                'delete' => true,
                'permissions' => ['book-delete']
            ]

        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $data['categories'] = $this->categoryService->getCategories()->select(['id', 'name'])->get();
        $data['publishers'] = $this->publisherService->getPublishers()->select(['id', 'name'])->get();
        $data['racks'] = $this->rackService->getRacks()->select(['id', 'rack_number'])->get();
        return view('backend.admin.book-management.book.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
    {
        try {
            $validated = $request->validated();
            $file = $request->validated('cover_image') && $request->hasFile('cover_image') ? $request->file('cover_image') : null;
            $pdf = $request->validated('file') && $request->hasFile('file') ? $request->file('file') : null;
            $this->bookService->createBook($validated, $file, $pdf);
            session()->flash('success', "Book created successfully");
        } catch (\Throwable $e) {
            session()->flash('error', "Book creation failed");
            throw $e;
        }
        return $this->redirectIndex();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $data = $this->bookService->getBook($id);
        $data['category_name'] = $data->category?->name;
        $data['publisher_name'] = $data->publisher?->name;
        $data['rack_number'] = $data->rack?->rack_number;
        $data['creater_name'] = $this->creater_name($data);
        $data['updater_name'] = $this->updater_name($data);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $data['book'] = $this->bookService->getBook($id);
        $data['categories'] = $this->categoryService->getCategories()->select(['id', 'name'])->get();
        $data['publishers'] = $this->publisherService->getPublishers()->select(['id', 'name'])->get();
        $data['racks'] = $this->rackService->getRacks()->select(['id', 'rack_number'])->get();
        return view('backend.admin.book-management.book.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookRequest $request, string $id)
    {

        try {
            $validated = $request->validated();
            $book = $this->bookService->getBook($id);
            $file = $request->validated('cover_image') && $request->hasFile('cover_image') ? $request->file('cover_image') : null;
            $pdf = $request->validated('file') && $request->hasFile('file') ? $request->file('file') : null;
            $this->bookService->updateBook($book, $validated, $file, $pdf);

            session()->flash('success', "Book updated successfully");
        } catch (\Throwable $e) {
            session()->flash('Book update failed');
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
            $book = $this->bookService->getBook($id);
            $this->bookService->delete($book);
            session()->flash('success', "Book deleted successfully");
        } catch (\Throwable $e) {
            session()->flash('Book delete failed');
            throw $e;
        }
        return $this->redirectIndex();
    }

    public function trash(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->bookService->getBooks()->onlyTrashed();

            return DataTables::eloquent($query)
                ->editColumn('category_id', fn($book) => $book->category?->name)
                ->editColumn('publisher_id', fn($book) => $book->publisher?->name)
                ->editColumn('rack_id', fn($book) => $book->rack?->rack_number)
                ->editColumn('status', fn($book) => "<span class='badge badge-soft {$book->status_color}'>{$book->status_label}</span>")
                ->editColumn('deleted_by', fn($book) => $this->deleter_name($book))
                ->editColumn('deleted_at', fn($book) => $book->deleted_at_formatted)
                ->editColumn('action', fn($book) => view('components.admin.action-buttons', [
                    'menuItems' => $this->trashedMenuItems($book),
                ])->render())
                ->rawColumns(['deleted_by', 'status', 'category_id', 'publisher_id', 'rack_id', 'deleted_at', 'action'])
                ->make(true);
        }

        return view('backend.admin.book-management.book.trash');
    }


    protected function trashedMenuItems($model): array
    {
        return [
            [
                'routeName' => 'bm.book.restore',
                'params' => [encrypt($model->id)],
                'label' => 'Restore',
                'permissions' => ['book-restore']
            ],
            [
                'routeName' => 'bm.book.permanent-delete',
                'params' => [encrypt($model->id)],
                'label' => 'Permanent Delete',
                'p-delete' => true,
                'permissions' => ['book-permanent-delete']
            ]

        ];
    }

    public function restore(string $id): RedirectResponse
    {
        try {
            $this->bookService->restore($id);
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
            $this->bookService->permanentDelete($id);
            session()->flash('success', "Service permanently deleted successfully");
        } catch (\Throwable $e) {
            session()->flash('Service permanent delete failed');
            throw $e;
        }
        return $this->redirectTrashed();
    }

    public function status(string $id)
    {
        $book = $this->bookService->getBook($id);

        $this->bookService->toggleStatus($book);
        session()->flash('success', 'Book status updated successfully!');
        return redirect()->back();
    }
}
