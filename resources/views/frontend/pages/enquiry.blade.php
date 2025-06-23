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
            class="relative min-h-screen overflow-hidden bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 flex items-center justify-center px-6">

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
                class="login-card dark:bg-gray-800 shadow-xl border-gray-50 border  rounded-2xl overflow-hidden  w-[1550px] relative pt-5 md:pb-0">

                <a href="{{ url('/') }}"
                    class="flex items-center justify-center absolute top-3 left-3  px-5 py-3 rounded-md animate-scalePulse text-gray-700 gap-2">
                    <i data-lucide="arrow-left"></i>
                    <span>Back To Home</span>
                </a>

                {{-- @if (session('success'))
                    <p class="text-green-600 text-center">{{ session('success') }}</p>
                @endif --}}

                <div class="flex flex-col lg:flex-row items-center">
                    <!-- Left Side: Form -->
                    <div class="w-full lg:w-1/2 p-8 lg:p-12">
                        <h2 class="text-2xl md:text-3xl font-bold text-center text-gray-800 dark:text-white mb-6">
                            {{ __('Query Form') }}
                        </h2>

                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form method="POST" action="{{ route('f.enquiry-store') }}" class="space-y-5">
                            @csrf

                            <div>
                                <label class="block text-base  font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('Name') }}
                                </label>
                                <input type="text" name="name" placeholder="Name" class="input"
                                    value="{{ old('name') }}" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>
                            <div>
                                <label class="block text-base  font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('Email') }}
                                </label>
                                <input type="email" name="email" placeholder="Email" class="input"
                                    value="{{ old('email') }}" />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                            </div>
                            <div>
                                <label class="block text-base  font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('Contact Number') }}
                                </label>
                                <input type="number" name="contact" placeholder="Contact" class="input"
                                    value="{{ old('contact') }}" />
                                <x-input-error class="mt-2" :messages="$errors->get('contact')" />
                            </div>
                            <div>
                                <label class="block text-base  font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('Address') }}
                                </label>
                                <textarea name="address" class="textarea" placeholder="Address" rows="1">{{ old('address') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('address')" />
                            </div>
                            <div>
                                <label class="block text-base  font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('Message') }} <small>{{ __('(Optional)') }}</small>
                                </label>
                                <textarea name="message" class="textarea" placeholder="Message" rows="1">{{ old('message') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('message')" />
                            </div>

                            <div>
                                <x-primary-button class="text-base !w-full py-6">
                                    {{ __('Send Now') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>

                    <!-- Right Side: Image -->
                    <div class="hidden lg:block lg:w-1/2 max-h-[70vh]">
                        <img src="{{ asset('/frontend/images/enquiry.jpg') }}" alt="Enquiry Image"
                            class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-frontend::layout>
