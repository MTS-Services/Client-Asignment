<x-frontend::layout>

    <x-slot name="title">
        {{ __('Admin Login') }}
    </x-slot>

    <x-slot name="breadcrumb">
        {{ __('Admin Login') }}
    </x-slot>

    <x-slot name="page_slug">
        admin-login
    </x-slot>
<<<<<<< Updated upstream
    <section class="min-h-[80vh] flex items-center justify-center  py-12 px-4 sm:px-6 lg:px-8">
        <div
            class="flex flex-col md:flex-row bg-white dark:bg-gray-800 border-gray-50 border dark:border-black shadow-xl rounded-2xl overflow-hidden w-[1550px]">
            <!-- Left: Image -->
=======
    <section class="min-h-screen flex items-center justify-center ">
        <div class="flex flex-col md:flex-row bg-white dark:bg-gray-800 border-gray-50 border dark:border-black shadow-xl rounded-2xl overflow-hidden w-[1550px]">
             <!-- Left: Image -->
>>>>>>> Stashed changes
            <div class="hidden md:block md:w-7/12">
                <img src="{{ asset('/frontend/images/admin.jpg') }}" alt="Admin Login Image"
                    class="w-full h-full object-cover">
            </div>
            <!-- Right: Login Form -->
            <div class="w-full md:w-2/5 p-8 md:p-12">
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-gray-800 dark:text-white">
                        {{ __('Admin Login') }}
                    </h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Login to manage the dashboard') }}
                    </p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4 text-sm text-green-600 dark:text-green-400 text-center"
                    :status="session('status')" />

                <!-- Form -->
                <form method="POST" action="{{ route('admin.login') }}" class="mt-6 space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" name="email" type="email" :value="old('email')" required autofocus
                            class="block mt-1 w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/50 sm:text-sm" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" name="password" type="password" required
                            class="block mt-1 w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/50 sm:text-sm" />
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me"
                            class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-gray-700 dark:focus:ring-offset-gray-800">
                            <span class="ml-2">{{ __('Remember me') }}</span>
                        </label>

                        @if (Route::has('admin.password.request'))
                            <a class="text-sm text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 underline"
                                href="{{ route('admin.password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Submit -->
                    <div>
                        <x-primary-button class="w-full py-2 justify-center">
                            {{ __('Log in') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>


        </div>
    </section>
    </x-frontend-layout>
