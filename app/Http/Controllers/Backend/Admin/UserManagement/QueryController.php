<?php

namespace App\Http\Controllers\Backend\Admin\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Traits\AuditRelationTraits;
use App\Services\Admin\UserManagement\QueryService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Query;
use Illuminate\Support\Facades\Crypt;

class QueryController extends Controller implements HasMiddleware
{
    use AuditRelationTraits;

    protected QueryService $queryService;

    public function __construct(QueryService $queryService)
    {
        $this->queryService = $queryService;
    }


    public static function middleware(): array
    {
        return [
            'auth:admin', // Applies 'auth:admin' to all methods

            // Permission middlewares using the Middleware class
            new Middleware('permission:user-list', only: ['index']),
            //add more permissions if needed
        ];
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $query = $this->queryService->getQueries();
            // $query->getCollection()->transform(function ($enquiry) {
            //     $enquiry->email = Crypt::decryptString($enquiry->email);
            //     $enquiry->contact = Crypt::decryptString($enquiry->contact);
            //     $enquiry->address = Crypt::decryptString($enquiry->address);
            //     $enquiry->message = $enquiry->message ? Crypt::decryptString($enquiry->message) : null;
            //     return $enquiry;
            // });
            // if ($status) {
            //     $query = $query->where('status', array_search($status, Query::statusList()))->verified();
            // }

            return DataTables::eloquent($query)
                ->editColumn('email', fn($enquiry) => e($enquiry->decrypted_email))
                ->editColumn('contact', fn($enquiry) => e($enquiry->decrypted_contact))
                ->editColumn('creater_id', fn($enquiry) => $this->creater_name($enquiry))
                ->editColumn('created_at', fn($enquiry) => $enquiry->created_at_formatted)
                ->editColumn('action', fn($enquiry) => view('components.admin.action-buttons', [
                    'menuItems' => $this->menuItems($enquiry),
                ])->render())
                ->rawColumns(['creater_id', 'created_at', 'action'])
                ->make(true);
        }

        return view('backend.admin.user-management.query.index');
    }

    protected function menuItems($model): array
    {
        return [
            [
                'routeName' => 'javascript:void(0)',
                'data-id' => encrypt($model->id),
                'className' => 'view',
                'label' => 'Details',
                'permissions' => ['query-list']
            ],
        ];
    }

    public function show(Request $request, string $id)
    {
        $data = $this->queryService->getQuery($id);
        $data['creater_name'] = $this->creater_name($data);
        $data['updater_name'] = $this->updater_name($data);
        return response()->json($data);
    }
}
