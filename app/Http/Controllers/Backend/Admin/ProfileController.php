<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PasswordRequest;
use App\Http\Requests\Admin\ProfileUapdateRequest;
use App\Http\Traits\AuditRelationTraits;
use App\Models\Admin;
use App\Services\Admin\AdminManagement\AdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class ProfileController extends Controller implements HasMiddleware
{
    use AuditRelationTraits;

    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('profile');
    }

    protected AdminService $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public static function middleware(): array
    {
        return [
            'auth:web',
        ];
    }

    public function showProfile()
    {
        $data['admin'] = $this->adminService->getadmin(encrypt(admin()->id));
        return view('backend.admin.profile-management.profile', $data);
    }
    public function updateProfile(ProfileUapdateRequest $request, string $id)
    {
        try {
            $validated = $request->validated();
            $validated['creater_id'] = admin()->id;
            $validated['creater_type'] = get_class(admin());
            $this->adminService->updateadmin($this->adminService->getadmin($id), $validated, $request->file('image'));
            session()->flash('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        return $this->redirectIndex();
    }

    public function showPasswordPage()
    {
        return view('backend.admin.profile-management.password');
    }
    public function updatePassword(PasswordRequest $request)
    {
        $admin = Admin::findOrFail(admin()->id);
        $validated = $request->validated();
        $admin->update($validated);
        session()->flash('success', 'Password updated successfully.');
        return redirect()->back()->with('success', 'Password updated successfully.');
    }
}
