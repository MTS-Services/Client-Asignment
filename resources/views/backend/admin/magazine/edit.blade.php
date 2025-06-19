<x-admin::layout>
    <x-slot name="title">{{ __('Edit Magazine') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Edit Magazine') }}</x-slot>
    <x-slot name="page_slug">magazine</x-slot>

    @push('css')
        {{-- <link rel="stylesheet" href="{{ asset('assets/css/filepond.css') }}"> --}}
    @endpush

    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Edit Magazine') }}</h2>
                <x-admin.primary-link href="{{ route('magazine.index') }}">{{ __('Back') }} <i data-lucide="undo-2"
                        class="w-4 h-4"></i> </x-admin.primary-link>
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <!-- Form Section -->
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('magazine.update', encrypt($magazine->id)) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                        <!-- Title -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Title') }}</p>
                            <label class="input flex items-center gap-2">

                                <input type="text" id="title" placeholder="Title" value="{{ $magazine->title }}"
                                    name="title" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <!-- Slug -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Slug') }}</p>
                            <label class="input flex items-center gap-2">
                                <input type="text" id="slug" name="slug" value="{{ $magazine->slug }}"
                                    placeholder="Slug" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('slug')" />
                        </div>
                        <div class="space-y-2">
                            <p class="label">{{ __('url') }}</p>
                            <label class="input flex items-center gap-2">
                                <input type="text" id="url" name="url" value="{{ $magazine->url }}"
                                    placeholder="Url" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('url')" />
                        </div>
                    </div>

                    <div class="space-y-2 sm:col-span-2">
                        <p class="label">{{ __('Image') }}</p>
                        <input type="file" name="cover_image" class="filepond" id="cover_image"
                            accept="image/jpeg, image/png, image/jpg, image/webp, image/svg">
                        <x-input-error class="mt-2" :messages="$errors->get('cover_image')" />
                    </div>
                    {{-- Description --}}
                    <div class="space-y-2 sm:col-span-2">
                        <p class="label">{{ __('Description') }}</p>
                        <textarea name="description" rows="4" placeholder="Description" class="textarea">{{ $magazine->description }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>
                    <div class="flex justify-end mt-5">
                        <x-admin.primary-button>{{ __('Update') }}</x-admin.primary-button>
                    </div>
                </form>
            </div>

            {{-- documentation will be loded here and add md:col-span-2 class --}}

        </div>
    </section>
    @push('js')
        <script src="{{ asset('assets/js/ckEditor.js') }}"></script>
        <script src="{{ asset('assets/js/filepond.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                file_upload(["#cover_image"], ["image/jpeg", "image/png", "image/jpg, image/webp, image/svg"], {
                    "#cover_image": "{{ $magazine->modified_image }}"
                });
            });
        </script>
    @endpush
</x-admin::layout>
