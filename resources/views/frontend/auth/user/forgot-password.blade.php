<x-frontend::layout>

    <x-slot name="title">
        {{ __('Forgot Password') }}
    </x-slot>

    <x-slot name="breadcrumb">
        {{ __('Forgot Password') }}
    </x-slot>

    <x-slot name="page_slug">
        forgot-password
    </x-slot>

    {{-- <section>
        <div class="min-h-[80vh] flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
            <div
                class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
                </div>
                <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                            :value="old('email')" required autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button>
                            {{ __('Email Password Reset Link') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </section> --}}

    <section class="py-20">
        <div class=" min-h-[75vh] flex items-center justify-center ">
            <div
                class="flex flex-col md:flex-row bg-white dark:bg-gray-800 shadow-xl border-gray-50 border shadow-top rounded-2xl overflow-hidden  w-[1550px] ">

                <!-- Left Side: Form -->
                <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
                    <h2 class="text-2xl md:text-3xl font-bold text-center text-gray-800 dark:text-white mb-6">
                        {{ __('Login to Your Account') }}
                    </h2>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form class="space-y-5" action="{{ route('password.email') }}" method="POST">
                        @csrf
                        <div>
                            @if (session('status'))
                                <div class="text-text-accent text-sm">
                                    {{ session('status') }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <label class="input px-0 pl-2">
                                <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                                        stroke="currentColor">
                                        <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                    </g>
                                </svg>
                                <input type="text" placeholder="Enter Your Email" name="email" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('email')"  />
                        </div>

                        <div class="mt-5 flex justify-center sm:justify-between items-center gap-5 flex-wrap">
                            <x-primary-button class="ms-3">
                            {{ __('Verify Email') }}
                        </x-primary-button>
                            <p class="text-center text-sm mt-4">
                                {{ __('Remember password?') }} <a href="{{ route('login') }}"
                                    class="text-primary font-medium">
                                    {{ __('Sign in') }} </a>
                            </p>
                            
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
