<x-frontend::layout>

    <x-slot name="title">
        {{ __('Reset Password') }}
    </x-slot>

    <x-slot name="breadcrumb">
        {{ __('Reset Password') }}
    </x-slot>

    <x-slot name="page_slug">
        reset-password
    </x-slot>

    <section
        class="relative min-h-screen overflow-hidden bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 flex items-center justify-center ">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 opacity-40 dark:opacity-20">
            <div class="floating-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
                <div class="shape shape-4"></div>
                <div class="shape shape-5"></div>
                <div class="shape shape-6"></div>
            </div>
        </div>

        <div
            class="flex flex-col md:flex-row max-h-[60vh] login-card  w-[1550px] bg-white dark:bg-gray-800 border-t border-gray-100 shadow-lg rounded-2xl overflow-hidden relative">
            <a href="{{ url('/') }}"
                class="flex items-center justify-center absolute top-3 left-3  px-5 py-3 rounded-md animate-scalePulse text-gray-700 gap-2">
                <i data-lucide="arrow-left"></i>
                <span>Back To Home</span>
            </a>
            @auth('web')
                <x-user.profile-navlink route="{{ route('logout') }}" logout='true' name="{{ __('Sign Out') }}" />
            @endauth
            <!-- Left: Form Section -->
            <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
                <h2 class="text-2xl md:text-3xl font-bold text-center text-gray-800 dark:text-white mb-6">
                    {{ __('Reset Your Password') }}
                </h2>

                <p class="text-sm text-gray-600 dark:text-gray-400 text-center mb-4">
                    {{ __('Please enter your email and new password to reset your account access.') }}
                </p>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <input id="email" name="email" type="email" placeholder="Enter your email"
                            autocomplete="email" required value="{{ request()->email }}"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('New Password')" />
                        <input id="password" name="password" type="password" placeholder="Enter your password"
                            autocomplete="password" required value="{{ old('password') }}"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mt-4">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                        <input id="password_confirmation" name="password_confirmation" type="password"
                            placeholder="Enter your confirm password" autocomplete="password_confirmation" required
                            value="{{ old('password_confirmation') }}"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <x-primary-button class="w-full justify-center py-6">
                            {{ __('Reset Password') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Right: Image Section -->
            <div class="hidden md:block md:w-1/2">
                <img src="{{ asset('/frontend/images/password.jpg') }}" alt="Reset Password Image"
                    class="w-full h-full object-cover">
            </div>
        </div>
    </section>
</x-frontend::layout>
