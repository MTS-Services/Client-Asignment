<x-admin::layout>
    <x-slot name="title">{{ __('Edit Author') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Edit Author') }}</x-slot>
    <x-slot name="page_slug">author</x-slot>


    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Edit Author') }}</h2>
                <x-admin.primary-link href="{{ route('author.index') }}">{{ __('Back') }} </x-admin.primary-link>
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <!-- Form Section -->
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('author.update', encrypt($author->id)) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <!-- Name -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Name') }}</p>
                            <label class="input flex items-center gap-2">
                                <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                                        stroke="currentColor">
                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </g>
                                </svg>
                                <input type="text" placeholder="Name" value="{{ $author->name }}" name="name"
                                    class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Nationality -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Nationality') }}</p>
                            <label class="input flex items-center gap-2">
                                <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                                        stroke="currentColor">
                                        <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                    </g>
                                </svg>
                                <input type="text" name="nationality" value="{{ $author->nationality }}"
                                    placeholder="Nationality" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('nationality')" />
                        </div>
                        {{-- Birth Date --}}
                        <div class="space-y-2">
                            <p class="label">{{ __('Birth Date') }}</p>
                            <label class="input flex items-center gap-2">
                                <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                                        stroke="currentColor">
                                        <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                    </g>
                                </svg>
                                <input type="date" name="birth_date" value="{{ $author->birth_date }}"
                                    placeholder="Birth Date" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('birth_date')" />
                        </div>
                        {{-- Death Date --}}
                        <div class="space-y-2">
                            <p class="label">{{ __('Death Date') }}</p>
                            <label class="input flex items-center gap-2">
                                <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                                        stroke="currentColor">
                                        <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                    </g>
                                </svg>
                                <input type="date" name="death_date" value="{{ $author->death_date }}"
                                    placeholder="Death Date" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('death_date')" />
                        </div>
                        {{-- Biography --}}
                        <div class="space-y-2 col-span-2">
                            <p class="label">{{ __('Biography') }}</p>
                            <textarea name="biography" rows="4" placeholder="Biography" class="w-full border-gray-300 dark:border-gray-600">{{ $author->biography }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('biography')" />
                        </div>
                    </div>
                    {{-- Image --}}
                    <div class="space-y-2 col-span-2">
                        <p class="label">{{ __('Image') }}</p>
                        <input type="file" name="image" class="filepond" id="image"
                            accept="image/jpeg, image/png, image/jpg, image/webp, image/svg">
                        <x-input-error class="mt-2" :messages="$errors->get('image')" />
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
        <script src="{{ asset('assets/js/filepond.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                file_upload(["#image"], ["image/jpeg", "image/png", "image/jpg, image/webp, image/svg"], {
                    "#image": "{{ $author->modified_image }}"
                });
            });
        </script>
    @endpush
</x-admin::layout>
