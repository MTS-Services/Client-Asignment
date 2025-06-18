<x-admin::layout>
    <x-slot name="title">Change Password</x-slot>
    <x-slot name="breadcrumb">Change Password</x-slot>
    <x-slot name="page_slug">change-password</x-slot>

    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Change Password') }}</h2>
                <x-user.primary-link href="{{ route('admin.dashboard') }}">{{ __('Back') }} <i data-lucide="undo-2"
                        class="w-4 h-4"></i> </x-user.primary-link>
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1 }}">
            <!-- Form Section -->
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('update-password', encrypt(admin()->id)) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-5 ">
                        <!-- Password -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Old Password') }}</p>
                            <label class="input relative flex items-center gap-2">
                                <svg class="h-[1em] opacity-50 ms-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                                        stroke="currentColor">
                                        <path
                                            d="M2.586 17.414A2 2 0 0 0 2 18.828V21a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h1a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h.172a2 2 0 0 0 1.414-.586l.814-.814a6.5 6.5 0 1 0-4-4z">
                                        </path>
                                        <circle cx="16.5" cy="7.5" r=".5" fill="currentColor"></circle>
                                    </g>
                                </svg>
                                <input :type="$store.password.showPassword ? 'text' : 'password'" name="old_password"
                                    placeholder="Old Password" class="flex-1" />
                                <button type="button"
                                    @click="$store.password.showPassword = !$store.password.showPassword ; $nextTick(() => lucide.createIcons())">
                                    <i :data-lucide="$store.password.showPassword ? 'eye-off' : 'eye'"
                                        class="w-4 h-4 me-2"></i>
                                </button>
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('old_password')" />

                        </div>

                        <!-- Password -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Password') }}</p>
                            <label class="input relative flex items-center gap-2">
                                <svg class="h-[1em] opacity-50 ms-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                                        stroke="currentColor">
                                        <path
                                            d="M2.586 17.414A2 2 0 0 0 2 18.828V21a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h1a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h.172a2 2 0 0 0 1.414-.586l.814-.814a6.5 6.5 0 1 0-4-4z">
                                        </path>
                                        <circle cx="16.5" cy="7.5" r=".5" fill="currentColor"></circle>
                                    </g>
                                </svg>
                                <input :type="$store.password.showPassword ? 'text' : 'password'" name="password"
                                    placeholder="Password" class="flex-1" />
                                <button type="button"
                                    @click="$store.password.showPassword = !$store.password.showPassword ; $nextTick(() => lucide.createIcons())">
                                    <i :data-lucide="$store.password.showPassword ? 'eye-off' : 'eye'"
                                        class="w-4 h-4 me-2"></i>
                                </button>
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('password')" />

                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Confirm Password') }}</p>
                            <label class="input flex items-center gap-2">
                                <svg class="h-[1em] opacity-50 ms-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                                        stroke="currentColor">
                                        <path
                                            d="M2.586 17.414A2 2 0 0 0 2 18.828V21a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h1a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h.172a2 2 0 0 0 1.414-.586l.814-.814a6.5 6.5 0 1 0-4-4z">
                                        </path>
                                        <circle cx="16.5" cy="7.5" r=".5" fill="currentColor"></circle>
                                    </g>
                                </svg>
                                <input :type="$store.password.showPassword ? 'text' : 'password'"
                                    name="password_confirmation" placeholder="Confirm Password" class="flex-1" />
                                <button type="button"
                                    @click="$store.password.showPassword = !$store.password.showPassword ; $nextTick(() => lucide.createIcons())">
                                    <i :data-lucide="$store.password.showPassword ? 'eye-off' : 'eye'"
                                        class="w-4 h-4 me-2"></i>
                                </button>
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
                        </div>
                        
                    </div>
                    <div class="flex justify-end mt-5">
                        <x-admin.primary-button>{{ __('Change Password') }}</x-admin.primary-button>
                    </div>
                </form>
            </div>

        </div>
    </section>
</x-admin::layout>
