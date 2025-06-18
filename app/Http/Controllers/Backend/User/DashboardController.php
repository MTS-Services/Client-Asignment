<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\Models\BookIssues;
use App\Models\Magazine;
use App\Models\Newspaper;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $data['my_books'] = BookIssues::issued()->self()->count();
        $data['book_requests'] = BookIssues::pending()->self()->count();
        $data['book_overdues'] = BookIssues::overdue()->self()->count();
        $data['book_lost'] = BookIssues::lost()->self()->count();
        $data['unpaid'] = BookIssues::unpaid()->self()->count();
        $data['available_magazines'] = Magazine::active()->count();
        $data['available_newspapers'] = Newspaper::active()->count();
        $data['fines'] = $data['fines'] = BookIssues::select(['fine_amount', 'fine_status'])->where('status', BookIssues::FINE_PAID)->orWhere('status', BookIssues::FINE_UNPAID)->self();


        return view('backend.user.dashboard', $data);
    }
}
