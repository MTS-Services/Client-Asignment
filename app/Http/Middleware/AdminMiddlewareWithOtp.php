<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddlewareWithOtp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // Get the authenticated admin instance.
        $admin = Auth::guard('admin')->user();

        // 2. Check if the admin's email is already verified.
        if (is_null($admin->email_verified_at)) {
            // 3. Prevent infinite redirect loops.
            // Allow access to the OTP verification routes themselves.
            if ($request->routeIs('admin.otp-verification') || $request->routeIs('admin.otp-resend') || $request->routeIs('admin.verify-otp') || $request->routeIs('admin.password.request') || $request->routeIs('admin.password.email')) {
                return $next($request);
            }

            // 4. If unverified and not on an allowed route, redirect to OTP verification page.
            return redirect()->route('admin.otp-verification')->with('status', 'Please verify your email address to access this page.');
        }

        // 5. If the email is verified, allow the request to continue.
        return $next($request);
    }
}
