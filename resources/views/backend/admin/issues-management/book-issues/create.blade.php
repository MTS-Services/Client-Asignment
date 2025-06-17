<x-admin::layout>
    <x-slot name="title">{{ __('Create Book Issue') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Create Book Issue') }}</x-slot>
    <x-slot name="page_slug">book_issues</x-slot>


    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Create Book Issue') }}</h2>
                <x-admin.primary-link href="{{ route('bm.book-issues.index') }}">{{ __('Back') }}
                </x-admin.primary-link>
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <!-- Form Section -->
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('bm.book-issues.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <!-- User -->
                        <div class="space-y-2">
                            <p class="label">{{ __('User') }}</p>
                            <select name="user_id" class="select select2">
                                <option value="" selected disabled>{{ __('Select User') }}</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
                        </div>
                        <!-- Book -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Book') }}</p>
                            <select name="book_id" class="select select2">
                                <option value="" selected disabled>{{ __('Select Book') }}</option>
                                @foreach ($books as $book)
                                    <option value="{{ $book->id }}"
                                        {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                        {{ $book->title }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('book_id')" />

                        </div>


                        {{-- Issue Date --}}
                        <div class="space-y-2">
                            <p class="label">{{ __('Issue Date') }}</p>
                            <label class="input flex items-center gap-2">
                                <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                                        stroke="currentColor">
                                        <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                    </g>
                                </svg>
                                <input type="date" name="issue_date" value="{{ old('issue_date') }}"
                                    placeholder="Birth Date" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('issue_date')" />
                        </div>
                        {{-- Due Date --}}
                        <div class="space-y-2">
                            <p class="label">{{ __('Due Date') }}</p>
                            <label class="input flex items-center gap-2">
                                <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                                        stroke="currentColor">
                                        <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                    </g>
                                </svg>
                                <input type="date" name="due_date" value="{{ old('due_date') }}"
                                    placeholder="Death Date" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('due_date')" />
                        </div>
                        {{-- Retun  Date
                        <div class="space-y-2">
                            <p class="label">{{ __('Return Date') }}</p>
                            <label class="input flex items-center gap-2">
                                <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                                        stroke="currentColor">
                                        <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                    </g>
                                </svg>
                                <input type="date" name="return_date" value="{{ old('return_date') }}"
                                    placeholder="Death Date" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('return_date')" />
                        </div> --}}

                        {{-- Notes --}}
                        <div class="space-y-2 col-span-2">
                            <p class="label">{{ __('Notes') }}</p>
                            <textarea name="notes" rows="4" placeholder="Notes" class="textarea">{{ old('notes') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('notes')" />
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
    @endpush
</x-admin::layout>
