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

    <section class="min-h-[80vh] flex items-center justify-center bg-gray-100 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row w-[1550px] bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden">
            <!-- Left: Image / Branding -->
             <div class="hidden md:block md:w-1/2">
                    <img src="{{ asset('/frontend/images/register.jpg') }}" alt="Login Image"
                        class="w-full h-full object-cover">
                </div>

            <!-- Right: Form -->
            <div class="w-full md:w-1/2 p-8 sm:p-10 md:p-12">
                <div class="text-center mb-6">
                    <h2 class="text-3xl font-bold text-gray-800 dark:text-white">
                        {{ __('Create your account') }}
                    </h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Already registered?') }}
                        <a href="{{ route('login') }}"
                            class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                            {{ __('Login here') }}
                        </a>
                    </p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('Name') }}
                        </label>
                        <input id="name" name="name" type="text" autocomplete="name" required
                            value="{{ old('name') }}"
                            class="mt-1 w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/50 sm:text-sm" />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('Email Address') }}
                        </label>
                        <input id="email" name="email" type="email" autocomplete="email" required
                            value="{{ old('email') }}"
                            class="mt-1 w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/50 sm:text-sm" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('Password') }}
                        </label>
                        <input id="password" name="password" type="password" autocomplete="new-password" required
                            class="mt-1 w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/50 sm:text-sm" />
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('Confirm Password') }}
                        </label>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            autocomplete="new-password" required
                            class="mt-1 w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/50 sm:text-sm" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                    </div>

                    <!-- Submit -->
                    <div>
                        {{-- <button type="submit"
                            class="w-full flex justify-center py-2 px-4 rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 transition-all font-medium text-sm shadow focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            {{ __('Register') }}
                        </button> --}}
                         <x-primary-button class=" !w-full">
                            {{ __('Register') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </section>

</x-frontend::layout>
