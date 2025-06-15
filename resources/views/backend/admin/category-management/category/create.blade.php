<x-admin::layout>
    <x-slot name="title">{{ __('Create Category') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Create Category') }}</x-slot>
    <x-slot name="page_slug">category</x-slot>

    @push('css')
        {{-- <link rel="stylesheet" href="{{ asset('assets/css/filepond.css') }}"> --}}
    @endpush

    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Create Category') }}</h2>
                <x-admin.primary-link href="{{ route('am.category.index') }}">{{ __('Back') }} </x-admin.primary-link>
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <!-- Form Section -->
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('am.category.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <!-- Name -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Name') }}</p>
                            <label class="input flex items-center gap-2">
                                <input type="text" placeholder="Name" value="{{ old('name') }}" name="name"
                                    class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <p class="label">{{ __('slug') }}</p>
                            <label class="input flex items-center gap-2">
                                <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                                        stroke="currentColor">
                                        <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                    </g>
                                </svg>
                                <input type="text" name="slug" value="{{ old('slug') }}" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('slug')" />
                        </div>
                        <div class="space-y-2">
                            <p class="label">{{ __('Description') }}</p>
                            <textarea name="description" id="description" cols="" rows="10" class="flex-1 input w-full h-24"></textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
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
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                file_upload(["#image"], ["image/jpeg", "image/png", "image/jpg, image/webp, image/svg"]);
            });
        </script>
    @endpush
</x-admin::layout>
