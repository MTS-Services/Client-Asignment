<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // if (user()->email_verified_at == null) {
        //     return redirect()->route('otp-verification');
        // }
        return view('backend.user.dashboard');
    }
}
