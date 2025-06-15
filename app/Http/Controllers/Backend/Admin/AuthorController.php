<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AuthorRequest;
use App\Http\Traits\AuditRelationTraits;
use App\Services\Admin\AuthorService;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;

class AuthorController extends Controller implements HasMiddleware
{
    use AuditRelationTraits;

    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('author.index');
    }

    protected function redirectTrashed(): RedirectResponse
    {
        return redirect()->route('author.trash');
    }

    protected AuthorService $authorService;

    public function __construct(AuthorService $authorService)
    {
        $this->authorService = $authorService;
    }

    public static function middleware(): array
    {
        return [
            'auth:admin', // Applies 'auth:admin' to all methods

            // Permission middlewares using the Middleware class
            new Middleware('permission:author-list', only: ['index']),
            new Middleware('permission:author-details', only: ['show']),
            new Middleware('permission:author-create', only: ['create', 'store']),
            new Middleware('permission:author-edit', only: ['edit', 'update']),
            new Middleware('permission:author-delete', only: ['destroy']),
            new Middleware('permission:author-trash', only: ['trash']),
            new Middleware('permission:author-restore', only: ['restore']),
            new Middleware('permission:author-permanent-delete', only: ['permanentDelete']),
            //add more permissions if needed
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->authorService->getAuthors();
            return DataTables::eloquent($query)
                ->editColumn('status', function ($author) {
                    return "<span class='badge badge-soft " . $author->status_color . "'>" . $author->status_label . "</span>";
                })
                ->editColumn('created_by', function ($author) {
                    return $this->creater_name($author);
                })
                ->editColumn('created_at', function ($author) {
                    return $author->created_at_formatted;
                })
                ->editColumn('action', function ($author) {
                    $menuItems = $this->menuItems($author);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['created_by', 'status', 'created_at', 'action'])
                ->make(true);
        }
        return view('backend.admin.author.index');
    }

    protected function menuItems($model): array
    {
        return [
            [
                'routeName' => 'javascript:void(0)',
                'data-id' => encrypt($model->id),
                'className' => 'view',
                'label' => 'Details',
                'permissions' => ['author-list', 'author-delete', 'author-status']
            ],
            [
                'routeName' => 'author.edit',
                'params' => [encrypt($model->id)],
                'label' => 'Edit',
                'permissions' => ['author-edit']
            ],
            [
                'routeName' => 'author.status',
                'params' => [encrypt($model->id)],
                'label' => $model->status_btn_label,
                'permissions' => ['author-status']
            ],
            [
                'routeName' => 'author.destroy',
                'params' => [encrypt($model->id)],
                'label' => 'Delete',
                'delete' => true,
                'permissions' => ['author-delete']
            ]

        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        //
        return view('backend.admin.author.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AuthorRequest $request)
    {
        try {
            $validated = $request->validated();
            $this->authorService->createAuthor($validated, $request->file('image'));
            session()->flash('success', "Author created successfully");
        } catch (\Throwable $e) {
            session()->flash('error', "Author creation failed");
            throw $e;
        }
        return $this->redirectIndex();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $data = $this->authorService->getAuthor($id);
        $data['creater_name'] = $this->creater_name($data);
        $data['updater_name'] = $this->updater_name($data);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $data['author'] = $this->authorService->getAuthor($id);
        return view('backend.admin.author.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AuthorRequest $request, string $id)
    {
        try {
            $validated = $request->validated();
            $author = $this->authorService->getAuthor($id);
            $this->authorService->updateAuthor($author, $validated, $request->file('image'));
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
            $author = $this->authorService->getAuthor($id);
            $this->authorService->delete($author);
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
            $query = $this->authorService->getAuthors()->onlyTrashed();
            return DataTables::eloquent($query)
                ->editColumn('status', function ($author) {
                    return "<span class='badge badge-soft " . $author->status_color . "'>" . $author->status_label . "</span>";
                })
                ->editColumn('deleted_by', function ($author) {
                    return $this->deleter_name($author);
                })
                ->editColumn('deleted_at', function ($author) {
                    return $author->deleted_at_formatted;
                })
                ->editColumn('action', function ($permission) {
                    $menuItems = $this->trashedMenuItems($permission);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['deleted_by', 'status', 'deleted_at', 'action'])
                ->make(true);
        }
        return view('backend.admin.author.trash');
    }

    protected function trashedMenuItems($model): array
    {
        return [
            [
                'routeName' => 'author.restore',
                'params' => [encrypt($model->id)],
                'label' => 'Restore',
                'permissions' => ['permission-restore']
            ],
            [
                'routeName' => 'author.permanent-delete',
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
            $this->authorService->restore($id);
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
            $this->authorService->permanentDelete($id);
            session()->flash('success', "Service permanently deleted successfully");
        } catch (\Throwable $e) {
            session()->flash('Service permanent delete failed');
            throw $e;
        }
        return $this->redirectTrashed();
    }


    public function status(string $id)
    {
        $author = $this->authorService->getAuthor($id);
        $this->authorService->toggleStatus($author);
        session()->flash('success', 'Author status updated successfully!');
        return redirect()->back();
    }
}
