<x-frontend::layout>

    <x-slot name="title">
        {{ __('Login') }}
    </x-slot>

    <x-slot name="breadcrumb">
        {{ __('Login') }}
    </x-slot>

    <x-slot name="page_slug">
        login
    </x-slot>

    {{-- <section>
        <div class="min-h-[80vh] flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
            <div
                class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                <h1>{{ __( 'User Login') }}</h1>
                <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
                </div>
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                            :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Password')" />

                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                            autocomplete="current-password" />

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="block mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox"
                                class="rounded-sm dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-xs focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                name="remember">
                            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                                href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif

                        <x-primary-button class="ms-3">
                            {{ __('Log in') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </section> --}}
    <section class="py-20">
        <div class=" min-h-[80vh] flex items-center justify-center  ">
            <div
                class="flex flex-col md:flex-row bg-white dark:bg-gray-800 shadow-xl border-gray-50 border  rounded-2xl overflow-hidden  w-[1550px]">

                <!-- Left Side: Form -->
                <div class="w-full md:w-1/2 p-8 md:p-12">
                    <h2 class="text-2xl md:text-3xl font-bold text-center text-gray-800 dark:text-white mb-6">
                        {{ __('Login to Your Account') }}
                    </h2>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('Username or Email') }}
                            </label>
                            <input type="text" name="login" placeholder="Username or Email"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600" />
                            <x-input-error class="mt-2" :messages="$errors->get('login')" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('Password') }}
                            </label>
                            <div class="relative">
                                <input type="password" name="password" placeholder="Password"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600" />
                                <button type="button"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white">
                                    <i class="fa-regular fa-eye-slash"></i>
                                </button>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('password')" />
                        </div>


                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="inline-flex items-center">
                                <input id="remember_me" type="checkbox"
                                    class="rounded-sm dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-xs focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                    name="remember">
                                <span
                                    class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-gray-100   "
                                    href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif
                            {{-- <a href="{{ route('password.request') }}"
                                class="text-sm text-blue-600 hover:underline dark:text-blue-400">
                                {{ __('Forgot password?') }}
                            </a> --}}
                        </div>

                        <div>
                            {{-- <button type="submit"
                                class="w-full py-2 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                {{ __('Login') }}
                            </button> --}}
                            <x-primary-button class="!w-full">
                            {{ __('Log in') }}
                        </x-primary-button>
                        </div>
                    </form>
                </div>

                <!-- Right Side: Image -->
                <div class="hidden md:block md:w-1/2">
                    <img src="{{ asset('/frontend/images/user.jpg') }}" alt="Login Image"
                        class="w-full h-full object-cover">
                </div>
            </div>
        </div>

    </section>
</x-frontend::layout>
