<?php

namespace App\Http\Controllers\Backend\Admin;

use Illuminate\Http\Request;
use App\Services\Admin\BookService;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BookRequest;
use Illuminate\Http\RedirectResponse;
use App\Http\Traits\AuditRelationTraits;
use App\Models\Book;
use App\Models\Category;
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
        return redirect()->route('book.index');
    }

    protected function redirectTrashed(): RedirectResponse
    {
        return redirect()->route('book.trash');
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
        if ($request->ajax()) {
            $query = $this->bookService->getBooks();
            return DataTables::eloquent($query)
                ->editColumn('category_id', function ($book) {
                    return $book->category?->name;
                })
                ->editColumn('publisher_id', function ($book) {
                    return $book->publisher?->name;
                })
                ->editColumn('rack_id', function ($book) {
                    return $book->rack?->rack_number;
                })
                ->editColumn('status', function ($book) {
                    return "<span class='badge badge-soft " . $book->status_color . "'>" . $book->status_label . "</span>";
                })
                ->editColumn('created_by', function ($book) {
                    return $this->creater_name($book);
                })
                ->editColumn('created_at', function ($book) {
                    return $book->created_at_formatted;
                })
                ->editColumn('action', function ($book) {
                    $menuItems = $this->menuItems($book);
                    return view('components.admin.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['created_by', 'status', 'rack_id', 'publisher_id', 'category_id', 'created_at', 'action'])
                ->make(true);
        }
        return view('backend.admin.book.index');
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
                'routeName' => 'book.edit',
                'params' => [encrypt($model->id)],
                'label' => 'Edit',
                'permissions' => ['book-edit']
            ],
            [
                'routeName' => 'book.status',
                'params' => [encrypt($model->id)],
                'label' => $model->status_label,
                'permissions' => ['book-status']
            ],
            [
                'routeName' => 'book.destroy',
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
        return view('backend.admin.book.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
    {
        try {
            $validated = $request->validated();
            $this->bookService->createBook($validated,  $request->file('cover_image'));
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
        return view('backend.admin.book.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookRequest $request, string $id)
    {
        
        try {
            $validated = $request->validated();
            $book = $this->bookService->getBook($id);
    
            $this->bookService->updateBook($book, $validated, $request->file('cover_image'));

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
             ->editColumn('category_id', function ($book) {
                    return $book->category?->name;
                })
                ->editColumn('publisher_id', function ($book) {
                    return $book->publisher?->name;
                })
                ->editColumn('rack_id', function ($book) {
                    return $book->rack?->rack_number;
                })
                ->editColumn('status', function ($book) {
                    return "<span class='badge badge-soft " . $book->status_color . "'>" . $book->status_label . "</span>";
                })
                ->editColumn('deleted_by', function ($book) {
                    return $this->deleter_name($book);
                })
                ->editColumn('deleted_at', function ($book) {
                    return $book->deleted_at_formatted;
                })
                ->editColumn('action', function ($permission) {
                    $menuItems = $this->trashedMenuItems($permission);
                    return view('components.admin.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['deleted_by', 'status', 'category_id', 'publisher_id', 'rack_id', 'deleted_at', 'action'])
                ->make(true);
        }
        return view('backend.admin.book.trash');
    }

    protected function trashedMenuItems($model): array
    {
        return [
            [
                'routeName' => 'book.restore',
                'params' => [encrypt($model->id)],
                'label' => 'Restore',
                'permissions' => ['book-restore']
            ],
            [
                'routeName' => 'book.permanent-delete',
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
}
