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
    <section>
        <div
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
                class="flex flex-col md:flex-row max-h-[60vh] bg-white dark:bg-gray-800 shadow-xl border-gray-50 border shadow-top rounded-2xl overflow-hidden login-card  w-[1550px] relative">
                <a href="{{ url('/') }}"
                    class="flex items-center justify-center absolute top-3 left-3  px-5 py-3 rounded-md animate-scalePulse text-gray-700 gap-2">
                    <i data-lucide="arrow-left"></i>
                    <span>Back To Home</span>
                </a>

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
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <div class="mt-5 flex justify-center sm:justify-between items-center gap-5 flex-wrap">
                            <x-primary-button class="text-base !w-full py-6">
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
