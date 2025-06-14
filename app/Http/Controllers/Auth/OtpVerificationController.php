<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User; // Make sure your User model is imported
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail; // Make sure your OtpMail Mailable is imported
use Illuminate\Support\Facades\Auth; // Import Auth facade
use Illuminate\Support\Facades\RateLimiter; // Import RateLimiter facade
use Illuminate\Validation\ValidationException; // Import for throwing validation exceptions
use Carbon\Carbon; // Import Carbon for time operations

class OtpVerificationController extends Controller
{
    /**
     * Helper to determine if the request is for a 'forgot password' flow.
     */
    protected function isForgot(Request $request): bool
    {
        return (bool) $request->input('forgot', false);
    }

    /**
     * Display the OTP verification view.
     * This method also handles initial OTP generation/sending if 'forgot' is true
     * and a new OTP is needed, respecting rate limits.
     */
    public function otp(Request $request)
    {
        $isForgot = $this->isForgot($request);

        if (!user()) {
            // If no user is authenticated, redirect to login or home.
            // This scenario should be rare if 'auth' middleware is applied to this route.
            return redirect()->route('login'); // Adjust to your login route
        }

        $user = User::findOrFail(user()->id); // Assuming the user is authenticated (logged in)
        // Get the last OTP sent timestamp for client-side countdown.
        // This relies on the 'last_otp_sent_at' column in your users table being a datetime cast.
        $lastOtpSentAt = $user->last_otp_sent_at ? $user->last_otp_sent_at->timestamp : null;

        // If it's a 'forgot' flow and no recent OTP has been sent, send one.
        // This acts as an initial send for the 'forgot' flow if the user lands here directly.
        // It respects the rate limiter to prevent immediate abuse on page load.
        if ($isForgot && !$lastOtpSentAt) { // Only send if it's forgot and no OTP has been sent yet
            $throttleKey = 'resend_otp_for_' . $user->id;

            if (!RateLimiter::tooManyAttempts($throttleKey, $perMinute = 1)) {
                RateLimiter::hit($throttleKey, $decayMinutes = 1); // Mark an attempt

                $otp = random_int(100000, 999999); // Use standard OTP generation
                $otpExpiresAt = now()->addMinutes(10); // OTP valid for 10 minutes

                $user->email_otp = $otp;
                $user->email_otp_expires_at = $otpExpiresAt;
                $user->last_otp_sent_at = now(); // Update the last sent timestamp
                $user->save();

                Mail::to($user->email)->send(new OtpMail($user, $user->email_otp));
                session()->flash('success', 'A new OTP has been sent to your email.');
                $lastOtpSentAt = $user->last_otp_sent_at->timestamp; // Update for view
            }
        }

        return view('auth.otp-verification', compact('isForgot', 'lastOtpSentAt'));
    }

    /**
     * Handles the OTP verification logic.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string', 'digits:6'], // Use string for validation, then compare
        ]);

        $isForgot = $this->isForgot($request);

        if (!user()) {
            // If no user is authenticated, this is an unexpected state for a verified route.
            // Throwing a validation exception will make Laravel handle the error redirection.
            throw ValidationException::withMessages([
                'otp' => 'Authentication error. Please log in again.'
            ]);
        }

        $user = User::findOrFail(user()->id);

        // Check if OTP is correct and not expired
        if ($user->email_otp !== $request->otp || ($user->email_otp_expires_at && $user->email_otp_expires_at->isPast())) {
            // If OTP is invalid or expired, throw a validation exception
            throw ValidationException::withMessages([
                'otp' => 'Invalid or expired OTP. Please try again.'
            ]);
        }

        // OTP is valid
        $user->email_otp = null; // Clear OTP
        $user->email_otp_expires_at = null; // Clear expiry

        if (!$isForgot) {
            $user->email_verified_at = now(); // Mark email as verified if not 'forgot' flow
            $user->status = \App\Models\AuthBaseModel::STATUS_ACTIVE; // Set user to active if not 'forgot' flow
        }
        $user->save();

        if ($isForgot) {
            // For 'forgot password' flow, redirect to password reset form
            // You might want to generate a password reset token here and redirect
            // For now, it just dumps the user as in your original code.
            // dd($user); // Remove this in production
            return redirect()->route('password.reset', ['token' => 'your_generated_token', 'email' => $user->email])
                ->with('success', 'OTP verified. Please reset your password.');
        }

        // Redirect to user dashboard after successful verification
        return redirect()->route('user.dashboard')->with('success', 'Email verified successfully!');
    }

    /**
     * Handles resending of OTP. This method is designed to be called via AJAX.
     */
    public function resend(Request $request)
    {
        $user = User::findOrFail(user()->id);

        $throttleKey = 'resend_otp_for_' . $user->id;

        if (RateLimiter::tooManyAttempts($throttleKey, $perMinute = 1)) {
            $secondsRemaining = RateLimiter::availableIn($throttleKey);
            return response()->json([
                'success' => false,
                'message' => 'Please wait before resending. Try again in ' . $secondsRemaining . ' seconds.',
                'retry_after' => $secondsRemaining
            ], 429); 
        }

        RateLimiter::hit($throttleKey, $decayMinutes = 1);

        // Generate a new OTP and expiry
        $otp = random_int(100000, 999999);
        $otpExpiresAt = now()->addMinutes(10); 

        $user->email_otp = $otp;
        $user->email_otp_expires_at = $otpExpiresAt;
        $user->last_otp_sent_at = now();
        $user->save();

        Mail::to($user->email)->send(new OtpMail($user, $user->email_otp));

        return response()->json([
            'success' => true,
            'message' => 'A new OTP has been sent to your email.',
            'last_sent_at' => $user->last_otp_sent_at->timestamp
        ]);
    }
}
