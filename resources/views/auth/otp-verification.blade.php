<x-frontend::layout>

    <x-slot name="title">
        {{ __('Verify Your Email Address') }}
    </x-slot>

    <x-slot name="breadcrumb">
        {{ __('Verify Your Email Address') }}
    </x-slot>

    <x-slot name="page_slug">
        verify-otp
    </x-slot>

    <x-otp-verification-form :verify-route="route('verify-otp', ['email' => $email])" :resend-route="route('otp-resend', isset($isForgot) ? ['forgot' => $isForgot, 'email' => $email] : ['email' => $email])" :last-otp-sent-at="$lastOtpSentAt ?? null" :is-frogot="$isForgot"
        :login-url="route('login')" />

</x-frontend::layout>
