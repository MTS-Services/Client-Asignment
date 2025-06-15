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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class OtpVerificationController extends Controller
{
    protected const OTP_EXPIRY_MINUTES = 2;
    protected const OTP_RESEND_THROTTLE = 1; // 1 request per minute

    /**
     * Determines if the request is part of a "forgot password" flow.
     */
    protected function isForgotPasswordFlow(Request $request): bool
    {
        return (bool) $request->input('forgot', false);
    }

    /**
     * Retrieves the user for OTP verification based on context.
     */
    protected function getVerificationUser(Request $request): ?User
    {
        if ($this->isForgotPasswordFlow($request)) {
            // dd(session('otp_verification_user_id'));
            $user =  User::findOrFail(session('otp_verification_user_id'));
            // dd($user);
            return $user;
        }

        return Auth::guard('web')->user();
    }

    /**
     * Generates a new OTP for the user.
     */
    protected function generateNewOtp(User $user): void
    {
        $user->email_otp = random_int(100000, 999999);
        $user->email_otp_expires_at = now()->addMinutes(self::OTP_EXPIRY_MINUTES);
        $user->last_otp_sent_at = now();
        $user->save();
    }

    /**
     * Sends OTP email to the user.
     */
    protected function sendOtpEmail(User $user): void
    {
        Mail::to($user->email)->send(new UserOtpMail($user, $user->email_otp));
    }

    /**
     * Gets the throttle key for rate limiting.
     */
    protected function getThrottleKey(User $user, string $type): string
    {
        return "otp_{$type}_{$user->id}";
    }

    /**
     * Handles OTP verification view and initial OTP sending.
     */
    public function otp(Request $request)
    {
        try {
            $isForgot = $this->isForgotPasswordFlow($request);
            $user = $this->getVerificationUser($request);

            if (!$user) {
                return $this->handleUserNotFound($isForgot);
            }

            if ($user->email_verified_at && !$isForgot) {
                return redirect()->route('user.dashboard')->with('info', 'Email already verified.');
            }

            $this->sendOtpIfNeeded($user, $isForgot);

            return view('auth.otp-verification', [
                'isForgot' => $isForgot,
                'lastOtpSentAt' => optional($user->last_otp_sent_at)->timestamp,
                'email' => $user->email,
            ]);
        } catch (Throwable $e) {
            return redirect()->back()->with('error', 'An error occurred. Please try again.');
        }
    }

    /**
     * Verifies the submitted OTP.
     */
    public function verify(Request $request)
    {
        $request->validate(['otp' => 'required|integer|digits:6']);

        try {
            DB::beginTransaction();

            $isForgot = $this->isForgotPasswordFlow($request);
            $user = $this->getVerificationUser($request);

            if (!$user) {
                throw ValidationException::withMessages([
                    'otp' => $this->getUserNotFoundMessage($isForgot)
                ]);
            }

            $this->validateOtp($user, $request->otp);

            $this->clearOtpData($user);

            if (!$isForgot) {
                $user->email_verified_at = now();
                $user->save();
                DB::commit();
                return redirect()->route('user.dashboard')->with('success', 'Email verified!');
            }

            $token = $this->createPasswordResetToken($user);
            DB::commit();

            return redirect()->route('password.reset', ['token' => $token])
                ->with('success', 'OTP verified. Please reset your password.');
        } catch (ValidationException $e) {
            Log::warning($e->getMessage());
            DB::rollBack();
            throw $e;
        } catch (Throwable $e) {
            Log::warning($e->getMessage());
            DB::rollBack();
            return redirect()->back()->with('error', 'Verification failed. Please try again.');
        }
    }

    /**
     * Resends the OTP.
     */
    public function resend(Request $request)
    {
        try {
            $isForgot = $this->isForgotPasswordFlow($request);
            $user = $this->getVerificationUser($request);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => $this->getUserNotFoundMessage($isForgot),
                ], 401);
            }

            $throttleKey = $this->getThrottleKey($user, 'resend');

            if (RateLimiter::tooManyAttempts($throttleKey, self::OTP_RESEND_THROTTLE)) {
                $seconds = RateLimiter::availableIn($throttleKey);
                return response()->json([
                    'success' => false,
                    'message' => "Please wait {$seconds} seconds before resending.",
                    'retry_after' => $seconds
                ], 429);
            }

            RateLimiter::hit($throttleKey);

            $this->generateNewOtp($user);
            $this->sendOtpEmail($user);

            return response()->json([
                'success' => true,
                'message' => 'New OTP sent to your email.',
                'last_sent_at' => $user->last_otp_sent_at->timestamp
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to resend OTP. Please try again.'
            ], 500);
        }
    }

    /**
     * Helper methods below
     */

    protected function sendOtpIfNeeded(User $user, bool $isForgot): void
    {
        $otpExpired = !$user->email_otp_expires_at || $user->email_otp_expires_at->isPast();
        $shouldSendOtp = !$user->last_otp_sent_at || $otpExpired || ($isForgot && !$user->last_otp_sent_at);

        if ($shouldSendOtp) {
            $throttleKey = $this->getThrottleKey($user, 'initial');

            if (!RateLimiter::tooManyAttempts($throttleKey, self::OTP_RESEND_THROTTLE)) {
                RateLimiter::hit($throttleKey);
                $this->generateNewOtp($user);
                $this->sendOtpEmail($user);
                session()->flash('success', 'Verification code sent to your email.');
            } else {
                $seconds = RateLimiter::availableIn($throttleKey);
                session()->flash('error', "Please wait {$seconds} seconds before resending.");
            }
        }
    }

    protected function validateOtp(User $user, string $otp): void
    {
        if ((string)$user->email_otp !== (string)$otp) {
            throw ValidationException::withMessages(['otp' => 'Invalid OTP.']);
        }

        if (!now()->isBefore($user->email_otp_expires_at)) {
            throw ValidationException::withMessages(['otp' => 'OTP has expired.']);
        }
    }

    protected function clearOtpData(User $user): void
    {
        $user->email_otp = null;
        $user->email_otp_expires_at = null;
        $user->last_otp_sent_at = null;
        $user->save();
    }

    protected function createPasswordResetToken(User $user): string
    {
        $token = Str::random(60);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => bcrypt($token),
                'created_at' => now()
            ]
        );

        session()->forget('otp_verification_user_id');
        return $token;
    }

    protected function handleUserNotFound(bool $isForgot)
    {
        if ($isForgot) {
            return redirect()->route('password.request')->withErrors([
                'email' => 'Please initiate password reset first.'
            ]);
        }
        return redirect()->route('login');
    }

    protected function getUserNotFoundMessage(bool $isForgot): string
    {
        return $isForgot
            ? 'Session expired. Please re-initiate password reset.'
            : 'Authentication error. Please log in again.';
    }
}
