<x-admin::layout>
    <x-slot name="title">{{ __('Create Author') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Create Author') }}</x-slot>
    <x-slot name="page_slug">author</x-slot>


    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Create Author') }}</h2>
                <x-admin.primary-link href="{{ route('bm.author.index') }}">{{ __('Back') }} <i data-lucide="undo-2" class="w-4 h-4"></i> </x-admin.primary-link>
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <!-- Form Section -->
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('bm.author.store') }}" method="POST" enctype="multipart/form-data">
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

                        <!-- Nationality -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Nationality') }}</p>
                            <label class="input flex items-center gap-2">
                               
                                <input type="text" name="nationality" value="{{ old('nationality') }}"
                                    placeholder="Nationality" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('nationality')" />
                        </div>
                        {{-- Birth Date --}}
                        <div class="space-y-2">
                            <p class="label">{{ __('Birth Date') }}</p>
                            <label class="input flex items-center gap-2">
                               
                                <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                                    placeholder="Birth Date" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('birth_date')" />
                        </div>
                        {{-- Death Date --}}
                        <div class="space-y-2">
                            <p class="label">{{ __('Death Date') }}</p>
                            <label class="input flex items-center gap-2">
                               
                                <input type="date" name="death_date" value="{{ old('death_date') }}"
                                    placeholder="Death Date" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('death_date')" />
                        </div>
                        {{-- Image --}}
                        <div class="space-y-2 sm:col-span-2">
                            <p class="label">{{ __('Image') }}</p>
                            <input type="file" name="image" class="filepond" id="image"
                                accept="image/jpeg, image/png, image/jpg, image/webp, image/svg">
                            <x-input-error class="mt-2" :messages="$errors->get('image')" />
                        </div>
                        {{-- Biography --}}
                        <div class="space-y-2 sm:col-span-2">
                            <p class="label">{{ __('Biography') }}</p>
                            <textarea name="biography" rows="4" placeholder="Biography"
                                class="textarea">{{ old('biography') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('biography')" />
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
