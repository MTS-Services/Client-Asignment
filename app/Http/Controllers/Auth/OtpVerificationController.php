<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserOtpMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\DB; // Make sure DB is imported if used elsewhere in your controller
use Illuminate\Support\Facades\Password; // Make sure Password facade is imported if used for reset tokens

class OtpVerificationController extends Controller
{
    /**
     * Determines if the request is part of a "forgot password" flow.
     * This relies on a 'forgot' input being present and true.
     */
    protected function isForgot(Request $request): bool
    {
        return (bool) $request->input('forgot', false);
    }

    /**
     * Helper to retrieve the user for OTP verification based on the context.
     *
     * @param Request $request
     * @return User|null
     */
    protected function getVerificationUser(Request $request): ?User
    {
        if ($this->isForgot($request)) {
            // For forgot password, try to get user ID from session
            // This session variable should be set by your ForgotPasswordController after email submission.
            $userId = session('otp_verification_user_id');
            if ($userId) {
                return User::find($userId);                
            }
        } else {
            // For authenticated users (e.g., after registration, or general unverified access)
            return Auth::guard('web')->user();
        }

        return null; // User not found in either scenario
    }

    /**
     * Displays the OTP verification view and handles initial OTP sending.
     * This method is responsible for ensuring an OTP is sent if needed.
     */
    public function otp(Request $request)
    {
        $isForgot = $this->isForgot($request);
        $user = $this->getVerificationUser($request);

        // If no user is found for verification (e.g., session expired, or didn't initiate forgot flow)
        if (!$user) {
            if ($isForgot) {
                return redirect()->route('password.request')->withErrors([
                    'email' => 'Please initiate the password reset process first.'
                ]);
            } else {
                return redirect()->route('login'); // Redirect to login for general unauthenticated access
            }
        }

        // Check if the user's email is already verified. If so, they shouldn't be here.
        // This is a safeguard if they directly access /otp-verification after being verified.
        if (!is_null($user->email_verified_at) && !$isForgot) {
            // If already verified and not a forgot password flow, redirect them away.
            return redirect()->route('user.dashboard')->with('info', 'Your email is already verified.');
        }

        $lastOtpSentAt = $user->last_otp_sent_at ? $user->last_otp_sent_at->timestamp : null;
        $otpExpired = $user->email_otp_expires_at ? $user->email_otp_expires_at->isPast() : true; // Assume expired if null

        // Determine if an OTP needs to be sent:
        // 1. If no OTP was ever sent, OR
        // 2. If the last sent OTP has expired, OR
        // 3. If it's a forgot password flow and no OTP has been sent yet (initial landing).
        if (!$lastOtpSentAt || $otpExpired || ($isForgot && !$lastOtpSentAt)) {

            $throttleKey = ($isForgot ? 'initial_otp_forgot_' : 'initial_otp_') . $user->id;

            if (RateLimiter::tooManyAttempts($throttleKey, $perMinute = 1)) {
                $secondsRemaining = RateLimiter::availableIn($throttleKey);
                session()->flash('error', 'Please wait before resending. Try again in ' . $secondsRemaining . ' seconds.');
            } else {
                RateLimiter::hit($throttleKey, $decayMinutes = 1);

                $user->email_otp = random_int(100000, 999999);
                $user->email_otp_expires_at = now()->addMinutes(2); // Set OTP validity (e.g., 2 minutes)
                $user->last_otp_sent_at = now(); // Update the last sent timestamp
                $user->save();

                Mail::to($user->email)->send(new UserOtpMail($user, $user->email_otp));
                session()->flash('success', 'A new verification code has been sent to your email.');
                $lastOtpSentAt = $user->last_otp_sent_at->timestamp; // Update for view
            }
        }

        // Pass data to the view
        $data['isForgot'] = $isForgot;
        $data['lastOtpSentAt'] = $lastOtpSentAt; // Used by frontend for cooldown
        $data['email'] = $user->email; // Useful to display the email being verified

        return view('auth.otp-verification', $data);
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
        $user = $this->getVerificationUser($request);

        if (!$user) {
            $message = $isForgot
                ? 'Your session has expired or user not found. Please re-initiate password reset.'
                : 'Authentication error. Please log in again.';
            throw ValidationException::withMessages([
                'otp' => $message
            ]);
        }

        // OTP comparison (ensure it's string vs string or integer vs integer if using integer column)
        // Given 'digits:6' validation, it's safer to cast one or ensure types match.
        // If email_otp column is string, compare directly. If it's integer, cast request->otp to int.
        if ((string)$user->email_otp !== (string)$request->otp) {
            throw ValidationException::withMessages([
                'otp' => 'Invalid OTP. Please try again.'
            ]);
        }

        // Check OTP expiry
        if (!now()->isBefore($user->email_otp_expires_at)) {
            throw ValidationException::withMessages([
                'otp' => 'OTP has expired. Please try again.'
            ]);
        }

        // OTP is valid: Clear OTP data
        $user->email_otp = null;
        $user->email_otp_expires_at = null;
        $user->last_otp_sent_at = null;
        $user->save();

        if (!$isForgot) {
            $user->email_verified_at = now();
            $user->save();
            return redirect()->route('user.dashboard')->with('success', 'Email verified successfully!');
        } else {
            session()->forget('otp_verification_user_id');
            
            // Create password reset token
            $token = \Illuminate\Support\Str::random(60);
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                [
                    'token' => \Illuminate\Support\Facades\Hash::make($token),
                    'created_at' => now()
                ]
            );
            return redirect()->route('password.reset', ['token' => $token])
                ->with('success', 'OTP verified. Please reset your password.');
        }
    }

    /**
     * Handles resending of OTP. This method is designed to be called via Axios.
     */
    public function resend(Request $request)
    {
        $isForgot = $this->isForgot($request);
        $user = $this->getVerificationUser($request);

        if (!$user) {
            if ($isForgot) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please re-initiate the password reset process.'
                ], 401);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required. Please log in.'
                ], 401);
            }
        }

        // Use a specific throttle key based on user and flow
        $throttleKey = ($isForgot ? 'resend_otp_forgot_' : 'resend_otp_') . $user->id;

        if (RateLimiter::tooManyAttempts($throttleKey, $perMinute = 1)) {
            $secondsRemaining = RateLimiter::availableIn($throttleKey);
            return response()->json([
                'success' => false,
                'message' => 'Please wait before resending. Try again in ' . $secondsRemaining . ' seconds.',
                'retry_after' => $secondsRemaining
            ], 429);
        }

        RateLimiter::hit($throttleKey, $decayMinutes = 1);

        $user->email_otp = random_int(100000, 999999);
        $user->email_otp_expires_at = now()->addMinutes(2); // Same expiry as initial send
        $user->last_otp_sent_at = now();
        $user->save();

        Mail::to($user->email)->send(new UserOtpMail($user, $user->email_otp));

        return response()->json([
            'success' => true,
            'message' => 'A new OTP has been sent to your email.',
            'last_sent_at' => $user->last_otp_sent_at->timestamp
        ]);
    }
}
