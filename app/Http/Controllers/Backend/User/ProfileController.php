<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function showProfile()
    {
        return view('backend.user.profile-management.profile');
    }

    public function editProfile(Request $request)
    {
        return view('backend.user.profile-management.profile');
    }
    public function updateProfile(Request $request)
    {
        return view('backend.user.profile-management.profile');
    }

    public function showPasswordPage()
    {
        return view('backend.user.profile-management.password');
    }
    public function updatePassword(Request $request)
    {
    }
}
