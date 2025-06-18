<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ProfileUpdateRequest;
use App\Services\Admin\UserManagement\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected UserService $userService;
    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('user.profile');
    }

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function showProfile()
    {
        $data['user'] = $this->userService->getUser(encrypt(user()->id));
        return view('backend.user.profile-management.profile', $data);
    }

    public function editProfile(Request $request)
    {
        return view('backend.user.profile-management.profile');
    }
    public function updateProfile(ProfileUpdateRequest $request, string $id)
    {
        try {
            $validated = $request->validated();
            $this->userService->updateUser($this->userService->getUser($id), $validated, $request->file('image'));
            session()->flash('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        return $this->redirectIndex();
    }

    /*************  ✨ Windsurf Command ⭐  *************/
    /**
     * Display the password update page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    /*******  5160aed3-0690-4075-a51c-9564a5f83239  *******/
    public function showPasswordPage()
    {
        return view('backend.user.profile-management.password');
    }
    public function updatePassword(Request $request) {}
}
