<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserLoginRequest;
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
        if (Auth::guard('web')->check()) {
            return redirect()->intended(route('user.dashboard', absolute: false));
        }

        return view('frontend.auth.user.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(UserLoginRequest $request): RedirectResponse
    {
        $key = $this->throttleKey($request);

        // Check if the user is currently locked out
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            session()->flash('error', "Too many login attempts. Please try again in {$seconds} seconds.");
            return redirect()->back()->withInput($request->only('email'));
        }

        try {
            $request->authenticate(); // This will call RateLimiter::hit() on failure

            RateLimiter::clear($key);
            $request->session()->regenerate();

            session()->flash('success', 'Login successful! Welcome back.');
            return redirect()->intended(route('user.dashboard', absolute: false));
        } catch (ValidationException $e) {
            $attempts = RateLimiter::attempts($key);
            $remaining = max(5 - $attempts, 0);

            Log::info("Login failed for: {$request->email}, Attempts: {$attempts}");

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
        Auth::guard('web')->logout();
        $request->session()->forget('password_hash_web');
        return redirect()->route('login');
    }

    /**
     * Get the rate limiting throttle key for the given request.
     */
    protected function throttleKey(Request $request): string
    {
        return strtolower($request->input('email')) . '|' . $request->ip();
    }
}
