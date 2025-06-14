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
    protected function isForgot(Request $request): bool
    {
        return (bool) $request->input('forgot', false);
    }


    public function otp(Request $request)
    {
        $isForgot = $this->isForgot($request);

        if (!user()) {
            return redirect()->route('login');
        }

        $user = User::findOrFail(user()->id);

        $lastOtpSentAt = $user->last_otp_sent_at ? $user->last_otp_sent_at->timestamp : null;

        if ($isForgot && !$lastOtpSentAt) {
            $throttleKey = 'resend_otp_for_' . $user->id;

            if (!RateLimiter::tooManyAttempts($throttleKey, $perMinute = 1)) {
                RateLimiter::hit($throttleKey, $decayMinutes = 1);

                $user->email_otp = random_int(100000, 999999);
                $user->email_otp_expires_at = now()->addMinutes(2);
                $user->last_otp_sent_at = now();
                $user->save();
                Mail::to($user->email)->send(new OtpMail($user, $user->email_otp));
                session()->flash('success', 'A new OTP has been sent to your email.');
                $lastOtpSentAt = $user->last_otp_sent_at->timestamp;
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
            'otp' => ['required', 'integer', 'digits:6'],
        ]);

        $isForgot = $this->isForgot($request);

        if (!user()) {
            throw ValidationException::withMessages([
                'otp' => 'Authentication error. Please log in again.'
            ]);
        }

        $user = User::findOrFail(user()->id);

        if ($user->email_otp != $request->otp) {
            throw ValidationException::withMessages([
                'otp' => 'Invalid OTP. Please try again.'
            ]);
        }
        if (!now()->isBefore($user->email_otp_expires_at)) {
            throw ValidationException::withMessages([
                'otp' => 'OTP has expired. Please try again.'
            ]);
        }

        $user->email_otp = null;
        $user->email_otp_expires_at = null;

        if (!$isForgot) {
            $user->email_verified_at = now();
        }
        $user->save();

        if ($isForgot) {
            return redirect()->route('user.change-password')
                ->with('success', 'OTP verified. Please reset your password.');
        }

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
        $user->email_otp = random_int(100000, 999999);
        $user->email_otp_expires_at = now()->addMinutes(2);
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
