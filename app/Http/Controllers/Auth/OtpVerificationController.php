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
use Illuminate\Support\Facades\Password;
use Throwable;
use Carbon\Carbon;

class OtpVerificationController extends Controller
{
    protected const OTP_EXPIRY_MINUTES = 2;
    protected const OTP_RESEND_THROTTLE = 1; // 1 request per minute
    protected const MAX_OTP_ATTEMPTS = 5;

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
        Log::debug('Session data in getVerificationUser', [
            'session_id' => session()->getId(),
            'session_user_id' => session('otp_verification_user_id'),
            'all_session' => session()->all()
        ]);

        if ($this->isForgotPasswordFlow($request)) {
            $userId = session('otp_verification_user_id');
            
            if (!$userId) {
                Log::error('Missing user ID in session');
                return null;
            }

            try {
                $user = User::withoutGlobalScopes()->find($userId);
                
                if (!$user) {
                    Log::error('User not found in database', [
                        'requested_id' => $userId,
                        'database_record' => DB::table('users')->find($userId)
                    ]);
                    return null;
                }
                
                return $user;
            } catch (\Exception $e) {
                Log::error('User retrieval failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return null;
            }
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
        try {
            Mail::to($user->email)->send(new UserOtpMail($user, $user->email_otp));
            Log::info('OTP email sent', ['user_id' => $user->id]);
        } catch (\Exception $e) {
            Log::error('Failed to send OTP email', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
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
                'otpExpiryMinutes' => self::OTP_EXPIRY_MINUTES
            ]);

        } catch (Throwable $e) {
            Log::error('OTP form error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
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

            Log::debug('OTP verification attempt', [
                'user_id' => $user ? $user->id : null,
                'input_otp' => $request->otp,
                'is_forgot' => $isForgot
            ]);

            if (!$user) {
                throw ValidationException::withMessages([
                    'otp' => $this->getUserNotFoundMessage($isForgot)
                ]);
            }

            // Check OTP attempt limit
            $attemptsKey = 'otp_attempts_' . $user->id;
            if (RateLimiter::tooManyAttempts($attemptsKey, self::MAX_OTP_ATTEMPTS)) {
                $seconds = RateLimiter::availableIn($attemptsKey);
                throw ValidationException::withMessages([
                    'otp' => "Too many attempts. Please try again in {$seconds} seconds."
                ]);
            }

            $this->validateOtp($user, $request->otp);
            RateLimiter::clear($attemptsKey);

            $this->clearOtpData($user);

            if (!$isForgot) {
                $user->email_verified_at = now();
                $user->save();
                DB::commit();
                
                Auth::login($user);
                return redirect()->route('user.dashboard')
                    ->with('success', 'Email verified successfully!');
            }

            $token = $this->createPasswordResetToken($user);
            DB::commit();

            return redirect()->route('password.reset', ['token' => $token])
                ->with('success', 'OTP verified. Please reset your password.');

        } catch (ValidationException $e) {
            DB::rollBack();
            RateLimiter::hit($attemptsKey ?? 'global_otp_attempts');
            throw $e;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('OTP verification failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
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
                'last_sent_at' => $user->last_otp_sent_at->timestamp,
                'expires_at' => $user->email_otp_expires_at
            ]);

        } catch (Throwable $e) {
            Log::error('OTP resend failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to resend OTP. Please try again.'
            ], 500);
        }
    }

    /**
     * Helper methods
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
        Log::debug('Validating OTP', [
            'stored_otp' => $user->email_otp,
            'input_otp' => $otp,
            'expires_at' => $user->email_otp_expires_at,
            'current_time' => now(),
            'is_expired' => now()->gt($user->email_otp_expires_at)
        ]);

        if ((string)$user->email_otp !== (string)$otp) {
            throw ValidationException::withMessages(['otp' => 'Invalid verification code.']);
        }

        if (now()->gt($user->email_otp_expires_at)) {
            throw ValidationException::withMessages(['otp' => 'Verification code has expired.']);
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
        $token = Password::createToken($user);
        session()->forget('otp_verification_user_id');
        return $token;
    }

    protected function getThrottleKey(User $user, string $type): string
    {
        return "otp_{$type}_{$user->id}";
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