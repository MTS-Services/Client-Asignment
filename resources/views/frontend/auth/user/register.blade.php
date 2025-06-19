<x-frontend::layout>

    <x-slot name="title">
        {{ __('Register') }}
    </x-slot>

    <x-slot name="breadcrumb">
        {{ __('Register') }}
    </x-slot>

    <x-slot name="page_slug">
        register
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
            class="flex flex-col md:flex-row w-[1550px] bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden relative login-card">
            <a href="{{ url('/') }}"
                class="flex items-center justify-center absolute top-3 left-3  px-5 py-3 rounded-md animate-scalePulse text-gray-700 gap-2">
                <i data-lucide="arrow-left"></i>
                <span>Back To Home</span>
            </a>
            <!-- Left: Image / Branding -->
            <div class="hidden md:block md:w-1/2 max-h-[70vh]">
                <img src="{{ asset('/frontend/images/register (2).png') }}" alt="Login Image"
                    class="w-full h-full object-cover">
            </div>

            <!-- Right: Form -->
            <div class="w-full md:w-1/2 p-8 sm:p-10 md:p-12">
                <div class="text-center mb-6">
                    <h2 class="text-3xl font-bold text-gray-800 dark:text-white">
                        {{ __('Let’s Get Started') }}
                    </h2>

                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-base font-medium text-gray-700 dark:text-gray-300 mb-3">
                            {{ __('Name') }}
                        </label>
                        <input id="name" name="name" type="text" placeholder="Enter your name"
                            autocomplete="name" required value="{{ old('name') }}"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600" />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>
                    {{-- <div>
                            <label class="block text-base font-medium text-gray-700 dark:text-gray-300 mb-3">
                                {{ __('Username or Email') }}
                            </label>
                            <input type="text" name="login" placeholder="Username or Email"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600" />
                            <x-input-error class="mt-2" :messages="$errors->get('login')" />
                        </div> --}}

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-base font-medium text-gray-700 dark:text-gray-300 mb-3">
                            {{ __('Email Address') }}
                        </label>
                        <input id="email" name="email" type="email" autocomplete="email" required
                            placeholder="Enter your email" value="{{ old('email') }}"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-base font-medium text-gray-700 dark:text-gray-300 mb-3">
                            {{ __('Password') }}
                        </label>
                        <input id="password" name="password" type="password" autocomplete="new-password" required
                            placeholder="Enter your password"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600" />
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation"
                            class="block text-base font-medium text-gray-700 dark:text-gray-300 mb-3">
                            {{ __('Confirm Password') }}
                        </label>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            placeholder="Confirm Password" autocomplete="new-password" required
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                    </div>

                    <!-- Submit -->
                    <div>
                        <x-primary-button class="text-base !w-full py-6">
                            {{ __('Register') }}
                        </x-primary-button>
                    </div>
                    <p class="mt-2 text-base text-gray-600 dark:text-gray-400">
                        {{ __('Already registered?') }}
                        <a href="{{ route('login') }}"
                            class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                            {{ __('Login here') }}
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </section>

</x-frontend::layout>
