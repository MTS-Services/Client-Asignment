<?php

namespace App\Http\Controllers\Backend\Admin\BookManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PublishManagement\PublisherRequest;
use App\Http\Traits\AuditRelationTraits;
use App\Models\Publisher;
use App\Services\Admin\PublishManagement\PublisherService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;

class PublisherController extends Controller implements HasMiddleware
{
    use AuditRelationTraits;
    protected PublisherService $publisherService;

    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('bm.publisher.index');
    }

    protected function redirectTrashed(): RedirectResponse
    {
        return redirect()->route('bm.publisher.trash');
    }



    public function __construct(PublisherService $publisherService)
    {
        $this->publisherService = $publisherService;
    }

    public static function middleware(): array
    {
        return [
            'auth:admin', // Applies 'auth:admin' to all methods

            // Permission middlewares using the Middleware class
            new Middleware('permission:publisher-list', only: ['index']),
            new Middleware('permission:publisher-details', only: ['show']),
            new Middleware('permission:publisher-create', only: ['create', 'store']),
            new Middleware('permission:publisher-edit', only: ['edit', 'update']),
            new Middleware('permission:publisher-delete', only: ['destroy']),
            new Middleware('permission:publisher-status', only: ['status']),
            new Middleware('permission:publisher-trash', only: ['trash']),
            new Middleware('permission:publisher-restore', only: ['restore']),
            new Middleware('permission:publisher-permanent-delete', only: ['permanentDelete']),
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
            $query = $this->publisherService->getPublishers();
            if ($status) {
                $query = $query->where('status', array_search($status, Publisher::statusList()));
            }

            return DataTables::eloquent($query)
                ->editColumn('status', fn($publisher) => "<span class='badge badge-soft {$publisher->status_color}'>{$publisher->status_label}</span>")
                ->editColumn('created_by', fn($publisher) => $this->creater_name($publisher))
                ->editColumn('created_at', fn($publisher) => $publisher->created_at_formatted)
                ->editColumn('action', fn($publisher) => view('components.admin.action-buttons', [
                    'menuItems' => $this->menuItems($publisher),
                ])->render())
                ->rawColumns(['created_by', 'created_at', 'status', 'action'])
                ->make(true);
        }

        return view('backend.admin.book-management.publisher.index');
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
                'routeName' => 'bm.publisher.edit',
                'params' => [encrypt($model->id)],
                'label' => 'Edit',
                'permissions' => ['publisher-edit']
            ],
            [
                'routeName' => 'bm.publisher.status',
                'params' => [encrypt($model->id)],
                'label' => $model->status_btn_label,
                'permissions' => ['publisher-status']
            ],

            [
                'routeName' => 'bm.publisher.destroy',
                'params' => [encrypt($model->id)],
                'label' => 'Delete',
                'delete' => true,
                'permissions' => ['publisher-delete']
            ]

        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        //
        return view('backend.admin.book-management.publisher.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PublisherRequest $request)
    {
        try {
            $validated = $request->validated();
            $this->publisherService->createPublisher($validated);
            session()->flash('success', "Publisher created successfully");
        } catch (\Throwable $e) {
            session()->flash('Publisher creation failed');
            throw $e;
        }
        return $this->redirectIndex();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $data = $this->publisherService->getPublisher($id);
        $data['creater_name'] = $this->creater_name($data);
        $data['updater_name'] = $this->updater_name($data);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['publisher'] = $this->publisherService->getPublisher($id);
        return view('backend.admin.book-management.publisher.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PublisherRequest $request, string $id)
    {
        try {
            $publisher = $this->publisherService->getPublisher($id);
            $validated = $request->validated();
            $this->publisherService->updatePublisher($publisher, $validated);
            session()->flash('success', "Publisher updated successfully");
        } catch (\Throwable $e) {
            session()->flash('Publisher update failed');
            throw $e;
        }
        return $this->redirectIndex();
    }

    public function status(string $id)
    {
        $publisher = $this->publisherService->getPublisher($id);
        $this->publisherService->toggleStatus($publisher);
        session()->flash('success', 'Publisher status updated successfully!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $publisher = $this->publisherService->getPublisher($id);
            $this->publisherService->delete($publisher);
            session()->flash('success', "Publisher deleted successfully");
        } catch (\Throwable $e) {
            session()->flash('Publisher delete failed');
            throw $e;
        }
        return $this->redirectIndex();
    }

    public function trash(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->publisherService->getPublishers()->onlyTrashed();

            return DataTables::eloquent($query)
                ->editColumn('deleted_by', fn($publisher) => $this->deleter_name($publisher))
                ->editColumn('deleted_at', fn($publisher) => $publisher->deleted_at_formatted)
                ->editColumn('action', fn($publisher) => view('components.admin.action-buttons', [
                    'menuItems' => $this->trashedMenuItems($publisher),
                ])->render())
                ->rawColumns(['deleted_by', 'deleted_at', 'action'])
                ->make(true);
        }

        return view('backend.admin.book-management.publisher.trash');
    }

    protected function trashedMenuItems($model): array
    {
        return [
            [
                'routeName' => 'bm.publisher.restore',
                'params' => [encrypt($model->id)],
                'label' => 'Restore',
                'permissions' => ['permission-restore']
            ],
            [
                'routeName' => 'bm.publisher.permanent-delete',
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
            $this->publisherService->restore($id);
            session()->flash('success', "Publisher restored successfully");
        } catch (\Throwable $e) {
            session()->flash('Publisher restore failed');
            throw $e;
        }
        return $this->redirectTrashed();
    }

    public function permanentDelete(string $id): RedirectResponse
    {
        try {
            $this->publisherService->permanentDelete($id);
            session()->flash('success', "Publisher permanently deleted successfully");
        } catch (\Throwable $e) {
            session()->flash('Publisher permanent delete failed');
            throw $e;
        }
        return $this->redirectTrashed();
    }
}
