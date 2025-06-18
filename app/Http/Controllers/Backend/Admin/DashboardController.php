<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Book;
use App\Models\BookIssues;
use App\Models\Category;
use App\Models\Magazine;
use App\Models\Newspaper;
use App\Models\Publisher;
use App\Models\Rack;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $data['active_admins'] = Admin::active()->verified()->count();
        $data['active_users'] = User::active()->verified()->count();
        $data['available_books'] = Book::available()->count();
        $data['available_magazines'] = Magazine::active()->count();
        $data['available_newspapers'] = Newspaper::active()->count();
        $data['book_requests'] = BookIssues::pending()->count();
        $data['book_issued'] = BookIssues::issued()->count();
        $data['book_overdues'] = BookIssues::overdue()->count();
        $data['book_lost'] = BookIssues::lost()->count();
        $data['publishers'] = Publisher::active()->count();
        $data['categories'] = Category::active()->count();
        $data['unpaid'] = BookIssues::unpaid()->count();
        $data['racks'] = Rack::count();
        $data['fines'] = BookIssues::select(['fine_amount', 'fine_status'])->where('status', BookIssues::FINE_PAID)->orWhere('status', BookIssues::FINE_UNPAID);
        return view('backend.admin.dashboard', $data);
    }
}
