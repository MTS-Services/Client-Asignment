<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class OtpVerificationController extends Controller
{
    public function otp()
    {
        if (user()->email_verified_at) {
            return redirect()->route('user.dashboard');
        }
        return view('auth.otp-verification');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'numeric', 'digits:6'],
        ]);

        $user = User::findOrFail(user()->id);

        if ($user->email_otp == $request->otp) {
            if (!now()->isBefore($user->email_otp_expires_at)) {
                session()->flash('error', 'OTP has expired. Please try again.');
                return redirect()->route('otp-verification');
            }
            $user->email_otp = null;
            $user->email_otp_expires_at = null;
            $user->email_verified_at = now();
            $user->save();
            return redirect()->route('user.dashboard');
        } else {
            session()->flash('error', 'Invalid OTP. Please try again.');
            return redirect()->route('otp-verification');
        }
    }

    public function resend()
    {
        $otp = random_int(100000, 999999);
        $email_otp_expires_at = now()->addMinutes(2);
        $user = User::findOrFail(user()->id);

        $user->email_otp = $otp;
        $user->email_otp_expires_at = $email_otp_expires_at;
        $user->save();

        Mail::to($user->email)->send(new OtpMail($user, $otp));

        session()->flash('success', 'OTP sent successfully!');
        return redirect()->route('otp-verification');
    }
}
