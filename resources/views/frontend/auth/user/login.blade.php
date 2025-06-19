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

    <section>
        <div
            class="relative min-h-screen overflow-hidden bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 flex items-center justify-center">

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
                class=" login-card flex flex-col md:flex-row dark:bg-gray-800 shadow-xl border-gray-50 border  rounded-2xl overflow-hidden  w-[1550px] items-center relative">

                <a href="{{ url('/') }}"
                    class="flex items-center justify-center absolute top-3 left-3  px-5 py-3 rounded-md animate-scalePulse text-gray-700 gap-2">
                    <i data-lucide="arrow-left"></i>
                    <span>Back To Home</span>
                </a>

                <!-- Left Side: Form -->
                <div class="w-full md:w-1/2 p-8 md:p-12">
                    <h2 class="text-2xl md:text-3xl font-bold text-center text-gray-800 dark:text-white mb-6">
                        {{ __('Login to Your Account') }}
                    </h2>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label class="block text-base  font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('Email') }}
                            </label>
                            <input type="text" name="email" placeholder="Email"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600" />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <div>
                            <label class="block text-base  font-medium text-gray-700 dark:text-gray-300 mb-1">
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
                        </div>

                        <div>
                            <x-primary-button class="text-base !w-full py-6">
                                {{ __('Log in') }}
                            </x-primary-button>
                        </div>
                        <p class="text-sm mt-4 text-end ">
                            {{ __('Don\'t have an account?') }} <a href="{{ route('register') }}"
                                class="text-primary font-medium hover:text-primary/70 deuration-300 ">
                                {{ __('Sign up') }} </a>
                        </p>
                    </form>
                </div>

                <!-- Right Side: Image -->
                <div class="hidden md:block md:w-1/2 max-h-[70vh]">
                    <img src="{{ asset('/frontend/images/user.jpg') }}" alt="Login Image"
                        class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </section>
</x-frontend::layout>
