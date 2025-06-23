<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\QueryRequest;
use App\Models\Query;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Throwable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class QueryController extends Controller
{
    public function enquiry(): View
    {
        return view('frontend.pages.enquiry');
    }

    public function store(QueryRequest $request): RedirectResponse
    {
        $lockoutKey = 'enquiry-lockout:' . $request->ip();
        $attemptsKey = 'enquiry-attempts:' . $request->ip();

        // Check if user is already locked out
        if (RateLimiter::tooManyAttempts($lockoutKey, 1)) {
            $seconds = RateLimiter::availableIn($lockoutKey);
            return back()->withErrors([
                'message' => "Too many Query submissions. Try again in {$seconds} seconds."
            ])->with('error', 'Too many Query submissions. Try again in ' . $seconds . ' seconds.');
        }

        // Check recent submission attempts (1 minute window)
        if (RateLimiter::tooManyAttempts($attemptsKey, 5)) {
            // Set lockout for 5 minutes when exceeding 5 attempts
            RateLimiter::hit($lockoutKey, 300); // 5 minute lockout

            return back()->withErrors([
                'message' => 'Too many Query submissions. You are locked out for 5 minutes.'
            ])->with('error', 'Too many Query submissions. You are locked out for 5 minutes.');
        }

        // Record new attempt (1 minute decay)
        RateLimiter::hit($attemptsKey, 60);

        try {
            // Create enquiry with encrypted sensitive data
            Query::create([
                'name' => $request->name,
                'email' => Crypt::encryptString($request->email),
                'contact' => Crypt::encryptString($request->contact),
                'address' => Crypt::encryptString($request->address),
                'message' => $request->message ? Crypt::encryptString($request->message) : null,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->with('success', 'Your enquiry has been submitted successfully! We will contact you soon.');
        } catch (\Exception $e) {
            Log::error('Enquiry submission failed: ' . $e->getMessage());
            return back()->withErrors(['message' => 'Something went wrong. Please try again.']);
        }
    }
}
