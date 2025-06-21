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
        // Rate limiting for enquiry submissions
        $key = 'enquiry-submit:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors([
                'message' => 'Too many enquiry submissions. Please try again later.'
            ]);
        }

        RateLimiter::hit($key, 300); // Block for 5 minutes after 5 attempts

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
