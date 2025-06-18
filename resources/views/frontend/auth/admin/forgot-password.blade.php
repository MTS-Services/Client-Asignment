<x-frontend::layout>
    <x-slot name="title">
        {{ __('Admin Forgot Password') }}
    </x-slot>

    <x-slot name="breadcrumb">
        {{ __('Admin Forgot Password') }}
    </x-slot>

    <x-slot name="page_slug">
        admin-forgot-password
    </x-slot>

    <section class="py-20">
        <div class="min-h-[80vh] flex items-center justify-center">
            <div class="flex flex-col md:flex-row bg-white dark:bg-gray-800 shadow-xl border-gray-50 border dark:border-black shadow-top rounded-2xl overflow-hidden w-[1550px]">

                <!-- Left Side: Form -->
                <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
                    <h2 class="text-2xl md:text-3xl font-bold text-center text-gray-800 dark:text-white mb-6">
                        {{ __('Admin Password Reset') }}
                    </h2>

                    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400 text-center">
                        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.') }}
                    </div>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form class="space-y-5" method="POST" action="{{ route('admin.password.email') }}">
                        @csrf

                        <div>
                            <label class="input px-0 pl-2">
                                <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none" stroke="currentColor">
                                        <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                    </g>
                                </svg>
                                <input type="email" name="email" placeholder="Enter Your Email" value="{{ old('email') }}" required autofocus class="w-full bg-transparent focus:outline-none ml-2">
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <div class="mt-5 flex justify-center sm:justify-between items-center gap-5 flex-wrap">
                            <x-primary-button class="ms-3">
                                {{ __('Verify Email') }}
                            </x-primary-button>

                            <p class="text-center text-sm mt-4">
                                {{ __('Remember password?') }} <a href="{{ route('admin.login') }}" class="text-primary font-medium">
                                    {{ __('Sign in') }}
                                </a>
                            </p>
                        </div>
                    </form>
                </div>

                <!-- Right Side: Image -->
                <div class="hidden md:block md:w-1/2">
                    <img src="{{ asset('/frontend/images/admin.jpg') }}" alt="Admin Reset Image" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </section>
</x-frontend::layout>
