<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\PasswordRequest;
use App\Http\Requests\User\ProfileUpdateRequest;
use App\Services\Admin\UserManagement\UserService;
use Illuminate\Http\RedirectResponse;

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
    public function updateProfile(ProfileUpdateRequest $request, string $id)
    {
        try {
            $validated = $request->validated();
            $validated['creater_id'] = user()->id;
            $validated['creater_type'] = get_class(user());
            $this->userService->updateUser($this->userService->getUser($id), $validated, $request->file('image'));
            session()->flash('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        return $this->redirectIndex();
    }

    public function showPasswordPage()
    {
        return view('backend.user.profile-management.password');
    }
    public function updatePassword(PasswordRequest $request)
    {
        $user = $this->userService->getUser(encrypt(user()->id));
        $validated = $request->validated();
        $validated['creater_id'] = user()->id;
        $validated['creater_type'] = get_class(user());
        $user->update($validated);
        session()->flash('success', 'Password updated successfully.');
        return redirect()->back()->with('success', 'Password updated successfully.');
    }
}
