<x-guest-layout>
    {{-- Otp Verification --}}
    <form method="POST" action="{{ route('verify-otp') }}">
        @csrf
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ __('Verify Your Email Address') }}
        </h1>

        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Please enter the verification code we sent to your email address.') }}
        </p>

        <div class="mt-4">
            <x-input-label for="otp" :value="__('Code')" />
            <x-text-input id="otp" class="block mt-1 w-full" type="text" name="otp" :value="old('otp')" required
                autofocus autocomplete="otp" />
            <x-input-error class="mt-2" :messages="$errors->get('otp')" />
        </div>

        <div class="flex items-center justify-between mt-4">

            <x-primary-link class="!btn-accent" href="{{ route('otp-resend') }}">
                {{ __('Resend Code') }}
            </x-primary-link>

            <x-primary-button>
                {{ __('Verify') }}
            </x-primary-button>
        </div>

    </form>
</x-guest-layout>
