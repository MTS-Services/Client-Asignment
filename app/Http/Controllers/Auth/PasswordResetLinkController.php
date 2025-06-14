<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            session()->flash('error', 'Email not found.');
            return redirect()->back();
        }

        $user->email_otp = random_int(100000, 999999);
        $user->email_otp_expires_at = now()->addMinutes(2);
        $user->last_otp_sent_at = now();
        $user->save();
        Mail::to($user->email)->send(new OtpMail($user, $user->email_otp));

        return redirect()->route('otp-verification', ['forgot' => true, 'email' => $user->email]);

        //     // We will send the password reset link to this user. Once we have attempted
        //     // to send the link, we will examine the response then see the message we
        //     // need to show to the user. Finally, we'll send out a proper response.
        //     $status = Password::sendResetLink(
        //         $request->only('email')
        //     );

        //     return $status == Password::RESET_LINK_SENT
        //                 ? back()->with('status', __($status))
        //                 : back()->withInput($request->only('email'))
        //                     ->withErrors(['email' => __($status)]);
    }
}
