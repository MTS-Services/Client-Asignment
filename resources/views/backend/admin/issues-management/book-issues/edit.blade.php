<x-admin::layout>
    <x-slot name="title">{{ __('Edit Book Issue') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Edit Book Issue') }}</x-slot>
    <x-slot name="page_slug">book_issue</x-slot>

    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Edit Book Issue') }}</h2>
                <x-admin.primary-link
                    href="{{ route('im.book-issues.index') }}">{{ __('Back') }}</x-admin.primary-link>
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1 {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <!-- Form Section -->
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('im.book-issues.update', encrypt($issue->id)) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <!-- User -->
                        <div class="space-y-2">
                            <p class="label">{{ __('User') }}</p>
                            <select name="user_id" class="w-full input border-gray-300 dark:border-gray-600">
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
                            <select name="book_id" class="w-full input border-gray-300 dark:border-gray-600">
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

                        {{-- Return Date --}}
                        {{-- <div class="space-y-2">
                            <p class="label">{{ __('Return Date') }}</p>
                            <label class="input flex items-center gap-2">                            
                                <input type="date" name="return_date"
                                    value="{{ old('return_date', $issue->return_date) }}" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('return_date')" />
                        </div> --}}
                        {{-- Due Date --}}
                        <div class="space-y-2">
                            <p class="label">{{ __('Due Date') }}</p>
                            <label class="input flex items-center gap-2">                            
                                <input type="date" name="due_date" value="{{ old('due_date', $issue->due_date) }}"
                                    class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('due_date')" />
                        </div>
                        {{-- fine_amount --}}
                        <div class="space-y-2">
                            <p class="label">{{ __('Fine Amount') }}</p>
                            <label class="input flex items-center gap-2">                            
                                <input type="decimal" name="fine_amount" value="{{ old('fine_amount', $issue->fine_amount) }}"
                                    class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('fine_amount')" />
                        </div>

                        {{-- Notes --}}
                        <div class="space-y-2 col-span-2">
                            <p class="label">{{ __('Notes') }}</p>
                            <textarea name="notes" rows="4" placeholder="Notes" class="w-full dark:bg-slate-900 border-gray-300 dark:border-gray-600">{{ old('notes', $issue->notes) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                        </div>
                        {{-- Fine Paid --}}
                        <div class="space-y-2">
                                <label class="inline-flex  items-center space-x-2">
                                    <input type="checkbox" name="fine_paid" value="1"
                                        {{ old('fine_paid', $issue->fine_paid ?? false) ? 'checked' : '' }}
                                        class="rounded border-gray-300  text-primary focus:ring focus:ring-primary/50 dark:border-gray-600">
                                    <span class="label">{{ __('Fine Paid') }}</span>
                                </label>
                                <x-input-error class="mt-2" :messages="$errors->get('fine_paid')" />
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
        <script src="{{ asset('assets/js/filepond.js') }}"></script>
    @endpush
</x-admin::layout>
