<x-admin::layout>
    <x-slot name="title">{{ __('Edit Book Issue') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Edit Book Issue') }}</x-slot>
    <x-slot name="page_slug">book_issues_{{ request('status') }}</x-slot>

    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Edit Book Issue') }}</h2>
                <x-admin.primary-link
                    href="{{ route('bim.book-issues.index', ['status' => request('status')]) }}">{{ __('Back') }} <i
                        data-lucide="undo-2" class="w-4 h-4"></i> </x-admin.primary-link>
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1 {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <!-- Form Section -->
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form
                    action="{{ route('bim.book-issues.update', [encrypt($issue->id), 'status' => request('status')]) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <!-- User -->
                        <div class="space-y-2">
                            <p class="label">{{ __('User') }}</p>
                            <select name="user_id" class="select select2">
                                <option value="" disabled>{{ __('Select User') }}</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('user_id', $issue->user_id) == $user->id ? 'selected' : '' }}>
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
                                <option value="" disabled>{{ __('Select Book') }}</option>
                                @foreach ($books as $book)
                                    <option value="{{ $book->id }}"
                                        {{ old('book_id', $issue->book_id) == $book->id ? 'selected' : '' }}>
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
                                <input type="date" name="issue_date"
                                    value="{{ old('issue_date', $issue->issue_date) }}" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('issue_date')" />
                        </div>
                        {{-- Due Date --}}
                        <div class="space-y-2">
                            <p class="label">{{ __('Due Date') }}</p>
                            <label class="input flex items-center gap-2">
                                <input type="date" name="due_date" value="{{ old('due_date', $issue->due_date) }}"
                                    class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('due_date')" />
                        </div>
                        {{-- Notes --}}
                        <div class="space-y-2 sm:col-span-2">
                            <p class="label">{{ __('Notes') }}</p>
                            <textarea name="notes" rows="4" placeholder="Notes" class="textarea  !px-3 no-ckeditor5">{{ old('notes', $issue->notes) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                        </div>
                    </div>

                    <div class="flex justify-end mt-5">
                        <x-admin.primary-button>{{ __('Update') }}</x-admin.primary-button>
                    </div>
                </form>
            </div>

            {{-- documentation will be loaded here and add md:col-span-2 class --}}

        </div>
    </section>
    @push('js')
        <script src="{{ asset('assets/js/ckEditor.js') }}"></script>
    @endpush
</x-admin::layout>
