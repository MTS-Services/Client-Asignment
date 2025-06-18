<x-admin::layout>
    <x-slot name="title">{{ __('Create Publisher') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Create Publisher') }}</x-slot>
    <x-slot name="page_slug">publisher</x-slot>

    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Create Publisher') }}</h2>
                <x-admin.primary-link href="{{ route('bm.publisher.index') }}">{{ __('Back') }} <i data-lucide="undo-2"
                        class="w-4 h-4"></i>
                </x-admin.primary-link>
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <!-- Form Section -->
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('bm.publisher.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <!-- Name -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Name') }}</p>
                            <label class="input flex items-center gap-2">

                                <input type="text" placeholder="Enter name" id="title"
                                    value="{{ old('name') }}" name="name" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>
                        <div class="space-y-2">
                            <p class="label">{{ __('Slug') }}</p>
                            <label class="input flex items-center gap-2">

                                <input type="text" placeholder="Enter slug" id="slug"
                                    value="{{ old('slug') }}" name="slug" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('slug')" />
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Email') }}</p>
                            <label class="input flex items-center gap-2">

                                <input type="email" name="email" value="{{ old('email') }}"
                                    placeholder="Enter email" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>
                        <!-- Phone -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Phone') }}</p>
                            <label class="input flex items-center gap-2">

                                <input type="text" name="phone" value="{{ old('phone') }}"
                                    placeholder="Enter phone number" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                        </div>

                        <!-- Website -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Website') }}</p>
                            <label class="input flex items-center gap-2">

                                <input type="text" name="website" value="{{ old('website') }}"
                                    placeholder="Enter website" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('website')" />
                        </div>

                        <!-- Address -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Address') }}</p>
                            <label class="input flex items-center gap-2">
                                <input type="text" value="{{ old('address') }}" id="address" name="address"
                                    class="form-control" placeholder="Enter address">
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('address')" />
                        </div>
                    </div>
                    <div class="flex justify-end mt-5">
                        <x-admin.primary-button>{{ __('Create') }}</x-admin.primary-button>
                    </div>
                </form>
            </div>

            {{-- documentation will be loded here and add md:col-span-2 class --}}

        </div>
    </section>
    @push('js')
        <script src="{{ asset('assets/js/filepond.js') }}"></script>
    @endpush
</x-admin::layout>
