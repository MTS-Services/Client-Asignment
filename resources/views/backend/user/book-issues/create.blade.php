<x-user::layout>
    <x-slot name="title">{{ __('Create Book Issue') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Create Book Issue') }}</x-slot>
    <x-slot name="page_slug">book_issues_{{ request('status') }}</x-slot>


    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">Request Book
                    {{ request('status') == 'Pending' ? 'Request' : request('status') }}</h2>
                <x-user.primary-link
                    href="{{ route('user.book-issues-list', ['status' => request('status')]) }}">{{ __('Back') }} <i
                        data-lucide="undo-2" class="w-4 h-4"></i> </x-user.primary-link>
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('user.book-issues-request') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 ">
                        <div class="space-y-2 ">
                            <p class="label">{{ __('Book') }}</p>
                            <select name="book_id" class="select select2 w-full">
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
                        <div class="space-y-2">
                            <p class="label">{{ __('Issue Date') }}</p>
                            <label class="input flex items-center gap-2">

                                <input type="date" name="issue_date" value="{{ old('issue_date') }}"
                                    placeholder="Birth Date" class="flex-1 !bg-transparent" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('issue_date')" />
                        </div>
                        <div class="space-y-2">
                            <p class="label">{{ __('Due Date') }}</p>
                            <label class="input flex items-center gap-2">

                                <input type="date" name="due_date" value="{{ old('due_date') }}"
                                    placeholder="Death Date" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('due_date')" />
                        </div>
                    </div>
                    <div class="space-y-2 sm:col-span-2 mt-4">
                        <p class="label">{{ __('Notes') }}</p>
                        <textarea name="notes" rows="4" placeholder="Notes" class="textarea block w-full  !px-3 no-ckeditor5">{{ old('notes') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                    </div>
                    <div class="flex justify-end mt-5">
                        <x-admin.primary-button>{{ __('Create') }}</x-admin.primary-button>
                    </div>
                </form>
            </div>

        </div>
    </section>
    @push('js')
        <script src="{{ asset('assets/js/ckEditor.js') }}"></script>
    @endpush
</x-user::layout>
