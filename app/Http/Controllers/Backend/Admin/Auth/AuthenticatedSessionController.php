<?php

namespace App\Http\Controllers\Backend\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AdminLoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View|RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        return view('frontend.auth.admin.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(AdminLoginRequest $request): RedirectResponse
    {

        $key = $this->throttleKey($request);

        // Check if the user is currently locked out
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            session()->flash('error', "Too many login attempts. Please try again in {$seconds} seconds.");
            return redirect()->back()->withInput($request->only('email'));
        }


        try {
            $request->authenticate();
            $request->session()->regenerate();

            session()->flash('success', 'Login successful! Welcome back to admin panel.');
            return redirect()->intended(route('admin.dashboard', absolute: false));
        } catch (ValidationException $e) {
            $key = $this->throttleKey($request);
            $attempts = RateLimiter::attempts($key);
            $remaining = max(5 - $attempts, 0);

            Log::info("Admin login failed", [
                'email' => $request->email,
                'attempts' => $attempts,
                'ip' => $request->ip()
            ]);

            if ($remaining > 0) {
                session()->flash('warning', "Invalid credentials. You have {$remaining} attempts remaining.");
            } else {
                session()->flash('error', "Account temporarily locked due to too many failed attempts. Try again later.");
            }

            return redirect()->back()->withInput($request->only('email'));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();
        $request->session()->forget('password_hash_admin');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        session()->flash('success', 'You have been logged out successfully.');
        return redirect()->route('admin.login');
    }

    /**
     * Get the rate limiting throttle key for the given request.
     */
    protected function throttleKey(Request $request): string
    {
        return 'admin_login:' . strtolower($request->input('email')) . '|' . $request->ip();
    }
}
