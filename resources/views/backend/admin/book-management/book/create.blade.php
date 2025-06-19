<x-admin::layout>
    <x-slot name="title">{{ __('Create Book') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Create Book') }}</x-slot>
    <x-slot name="page_slug">book</x-slot>


    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Create Book') }}</h2>
                <x-admin.primary-link href="{{ route('bm.book.index') }}">{{ __('Back') }} <i data-lucide="undo-2"
                        class="w-4 h-4"></i> </x-admin.primary-link>
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <!-- Form Section -->
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('bm.book.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <!-- Title -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Title') }}</p>
                            <label class="input flex items-center gap-2">

                                <input type="text" placeholder="Title" id="title" value="{{ old('title') }}"
                                    name="title" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>
                        <!-- Slug -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Slug') }}</p>
                            <label class="input flex items-center gap-2">

                                <input type="text" placeholder="Slug" id="slug" value="{{ old('slug') }}"
                                    name="slug" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('slug')" />
                        </div>

                        <!-- Isbn -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Isbn') }}</p>
                            <label class="input flex items-center gap-2">

                                <input type="text" name="isbn" value="{{ old('isbn') }}" placeholder="Isbn"
                                    class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('isbn')" />
                        </div>
                        {{-- Publication Date --}}
                        <div class="space-y-2">
                            <p class="label">{{ __('Publication Date') }}</p>
                            <label class="input flex items-center gap-2">

                                <input type="date" name="publication_date" value="{{ old('publication_date') }}"
                                    placeholder="Publication Date" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('publication_date')" />
                        </div>

                        {{-- Category --}}
                        <div class="space-y-2">
                            <p class="label">{{ __('Category') }}</p>
                            <select name="category_id" class="select select2">
                                <option value="" selected disabled>{{ __('Select Category') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                        </div>
                        {{-- Publisher --}}
                        <div class="space-y-2">
                            <p class="label">{{ __('Publisher') }}</p>
                            <select name="publisher_id" class="select select2">
                                <option value="" selected disabled>{{ __('Select Publisher') }}</option>
                                @foreach ($publishers as $publisher)
                                    <option value="{{ $publisher->id }}"
                                        {{ old('publisher_id') == $publisher->id ? 'selected' : '' }}>
                                        {{ $publisher->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('publisher_id')" />
                        </div>
                        {{-- Rack --}}
                        <div class="space-y-2">
                            <p class="label">{{ __('Rack') }}</p>
                            <select name="rack_id" class="select select2">
                                <option value="" selected disabled>{{ __('Select Rack') }}</option>
                                @foreach ($racks as $rack)
                                    <option value="{{ $rack->id }}"
                                        {{ old('rack_id') == $rack->id ? 'selected' : '' }}>
                                        {{ $rack->rack_number }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('rack_id')" />
                        </div>
                        {{-- Image --}}
                        <div class="space-y-2 sm:col-span-2">
                            <p class="label">{{ __('Image') }}</p>
                            <input type="file" name="cover_image" class="filepond" id="cover_image"
                                accept="image/jpeg, image/png, image/jpg, image/webp, image/svg">
                            <x-input-error class="mt-2" :messages="$errors->get('cover_image')" />
                        </div>
                        {{-- File --}}
                        <div class="space-y-2 sm:col-span-2">
                            <p class="label">{{ __('File') }}</p>
                            <input type="file" name="file" class="filepond" id="file"
                                accept="application/pdf">
                            <x-input-error class="mt-2" :messages="$errors->get('file')" />
                        </div>
                        <!-- Language -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Language') }}</p>
                            <label class="input flex items-center gap-2">

                                <input type="text" name="language" value="{{ old('language') }}"
                                    placeholder="Language" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('language')" />
                        </div>
                        <!-- Price -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Price') }}</p>
                            <label class="input flex items-center gap-2">

                                <input type="text" name="price" value="{{ old('price') }}" placeholder="Price"
                                    class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('price')" />
                        </div>
                        <!-- Total Copies -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Total Copies') }}</p>
                            <label class="input flex items-center gap-2">
                                <input type="text" name="total_copies" value="{{ old('total_copies') }}"
                                    placeholder="Total copies" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('total_copies')" />
                        </div>
                        <!-- Available Total -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Available Copies') }}</p>
                            <label class="input flex items-center gap-2">

                                <input type="text" name="available_copies" value="{{ old('available_copies') }}"
                                    placeholder="Available Copies" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('available_copies')" />
                        </div>
                        {{-- Description --}}
                        <div class="space-y-2 sm:col-span-2">
                            <p class="label">{{ __('Description') }}</p>
                            <textarea name="description" rows="4" placeholder="Description" class="textarea">{{ old('description') }}</textarea>
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
        <script src="{{ asset('assets/js/ckEditor.js') }}"></script>
        <script src="{{ asset('assets/js/filepond.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                file_upload(["#cover_image"], ["image/jpeg", "image/png", "image/jpg, image/webp, image/svg"]);
                file_upload(["#file"], ["application/pdf"]);
            });
        </script>
    @endpush
</x-admin::layout>
