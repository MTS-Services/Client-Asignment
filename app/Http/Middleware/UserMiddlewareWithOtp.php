<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddlewareWithOtp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Ensure the user is authenticated.
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login');
        }

        // Get the authenticated user instance.
        $user = Auth::guard('web')->user();

        // 2. Check if the user's email is already verified.
        if (is_null($user->email_verified_at)) {
            // 3. Prevent infinite redirect loops.
            // Allow access to the OTP verification routes themselves.
            if ($request->routeIs('otp-verification') || $request->routeIs('otp-resend') || $request->routeIs('verify-otp') || $request->routeIs('password.request') || $request->routeIs('password.email')) {
                return $next($request);
            }

            // 4. If unverified and not on an allowed route, redirect to OTP verification page.
            return redirect()->route('otp-verification')->with('status', 'Please verify your email address to access this page.');
        }

        // 5. If the email is verified, allow the request to continue.
        return $next($request);
    }
}
