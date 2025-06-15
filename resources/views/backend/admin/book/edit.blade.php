<x-admin::layout>
    <x-slot name="title">{{ __('Edit Book') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Edit Book') }}</x-slot>
    <x-slot name="page_slug">book</x-slot>

    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Edit Book') }}</h2>
                <x-admin.primary-link href="{{ route('book.index') }}">{{ __('Back') }}</x-admin.primary-link>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-1 {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <!-- Form Section -->
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('book.update', encrypt($book->id)) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <!-- Title -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Title') }}</p>
                            <label class="input flex items-center gap-2">
                                <input type="text" name="title" value="{{ old('title', $book->title) }}" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <!-- Slug -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Slug') }}</p>
                            <label class="input flex items-center gap-2">
                                <input type="text" name="slug" value="{{ old('slug', $book->slug) }}" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('slug')" />
                        </div>

                        <!-- Isbn -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Isbn') }}</p>
                            <label class="input flex items-center gap-2">
                                <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('isbn')" />
                        </div>

                        <!-- Publication Date -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Publication Date') }}</p>
                            <label class="input flex items-center gap-2">
                                <input type="date" name="publication_date" value="{{ old('publication_date', $book->publication_date) }}" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('publication_date')" />
                        </div>

                        <!-- Category -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Category') }}</p>
                            <select name="category_id" class="w-full border-gray-300 dark:border-gray-600">
                                <option disabled>{{ __('Select Category') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                        </div>

                        <!-- Publisher -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Publisher') }}</p>
                            <select name="publisher_id" class="w-full border-gray-300 dark:border-gray-600">
                                <option disabled>{{ __('Select Publisher') }}</option>
                                @foreach ($publishers as $publisher)
                                    <option value="{{ $publisher->id }}" {{ old('publisher_id', $book->publisher_id) == $publisher->id ? 'selected' : '' }}>
                                        {{ $publisher->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('publisher_id')" />
                        </div>

                        <!-- Rack -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Rack') }}</p>
                            <select name="rack_id" class="w-full border-gray-300 dark:border-gray-600">
                                <option disabled>{{ __('Select Rack') }}</option>
                                @foreach ($racks as $rack)
                                    <option value="{{ $rack->id }}" {{ old('rack_id', $book->rack_id) == $rack->id ? 'selected' : '' }}>
                                        {{ $rack->rack_number }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('rack_id')" />
                        </div>

                        <!-- Image -->
                        <div class="space-y-2 col-span-2">
                            <p class="label">{{ __('Image') }}</p>
                            <input type="file" name="image" class="filepond" id="image"
                                accept="image/jpeg, image/png, image/jpg, image/webp, image/svg">
                            @if ($book->cover_image)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" class="h-24 rounded" alt="Current Cover Image">
                                </div>
                            @endif
                            <x-input-error class="mt-2" :messages="$errors->get('image')" />
                        </div>

                        <!-- Language -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Language') }}</p>
                            <label class="input flex items-center gap-2">
                                <input type="text" name="language" value="{{ old('language', $book->language) }}" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('language')" />
                        </div>

                        <!-- Price -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Price') }}</p>
                            <label class="input flex items-center gap-2">
                                <input type="text" name="price" value="{{ old('price', $book->price) }}" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('price')" />
                        </div>

                        <!-- Total Copies -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Total Copies') }}</p>
                            <label class="input flex items-center gap-2">
                                <input type="text" name="total_copies" value="{{ old('total_copies', $book->total_copies) }}" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('total_copies')" />
                        </div>

                        <!-- Available Copies -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Available Copies') }}</p>
                            <label class="input flex items-center gap-2">
                                <input type="text" name="available_copies" value="{{ old('available_copies', $book->available_copies) }}" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('available_copies')" />
                        </div>

                        <!-- Description -->
                        <div class="space-y-2 col-span-2">
                            <p class="label">{{ __('Description') }}</p>
                            <textarea name="description" rows="4" class="w-full border-gray-300 dark:border-gray-600">{{ old('description', $book->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>
                    </div>

                    <div class="flex justify-end mt-5">
                        <x-admin.primary-button>{{ __('Update') }}</x-admin.primary-button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    @push('js')
        <script src="{{ asset('assets/js/filepond.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                file_upload(["#image"], ["image/jpeg", "image/png", "image/jpg", "image/webp", "image/svg"]);
            });
        </script>
    @endpush
</x-admin::layout>
