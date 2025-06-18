<x-admin::layout>
    <x-slot name="title">{{ __('Book Issues Lost List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Book Issues Lost List') }}</x-slot>
    <x-slot name="page_slug">book_issues_{{ request('status') }}</x-slot>

    <section>



        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Lost Book Issue') }}</h2>
                <x-admin.primary-link
                    href="{{ route('bim.book-issues.index', ['status' => request('status')]) }}">{{ __('Back') }} <i
                        data-lucide="undo-2" class="w-4 h-4"></i></x-admin.primary-link>
            </div>
        </div>
        <div class="glass-card shadow rounded-xl p-6 mb-6 bg-white dark:bg-[#19221F]">
            <h2 class="text-xl font-bold text-text-black dark:text-text-white mb-4">📚 Book Issue Details</h2>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div class="bg-gray-100 dark:bg-slate-900 p-4 rounded-lg">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">Issue Code</p>
                    <p class="text-base font-medium mt-1 text-gray-800 dark:text-gray-200">{{ $issue_lost->issue_code }}
                    </p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-900 p-4 rounded-lg">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">User</p>
                    <p class="text-base font-medium mt-1 text-gray-800 dark:text-gray-200">
                        {{ $issue_lost->user?->name }}</p>
                </div>

                <div class="bg-gray-100 dark:bg-slate-900 p-4 rounded-lg">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">Book</p>
                    <p class="text-base font-medium mt-1 text-gray-800 dark:text-gray-200">
                        {{ $issue_lost->book?->title }}</p>
                </div>

                <div class="bg-gray-100 dark:bg-slate-900 p-4 rounded-lg">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">Issued By</p>
                    <p class="text-base font-medium mt-1 text-gray-800 dark:text-gray-200">
                        {{ $issue_lost->issuedBy?->name }}</p>
                </div>

                <div class="bg-gray-100 dark:bg-slate-900 p-4 rounded-lg">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">Issued Date</p>
                    <p class="text-base font-medium mt-1 text-gray-800 dark:text-gray-200">
                        {{ $issue_lost->issue_date }}</p>
                </div>

                <div class="bg-gray-100 dark:bg-slate-900 p-4 rounded-lg">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">Due Date</p>
                    <p class="text-base font-medium mt-1 text-gray-800 dark:text-gray-200">{{ $issue_lost->due_date }}
                    </p>
                </div>
                @if ($issue_lost->notes)
                    <div class="bg-gray-100 dark:bg-slate-900 p-4 rounded-lg col-span-2">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">Note</p>
                        <p class="text-base font-medium mt-1 text-gray-800 dark:text-gray-200">{{ $issue_lost->notes }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1 {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <!-- Form Section -->
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white mb-4"> Book Lost Form</h2>

                <form
                    action="{{ route('bim.book-issues.update-lost', [encrypt($issue_lost->id), 'status' => request('status')]) }}"
                    method="POST">
                    @csrf
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                        <div class="space-y-2">
                            <p class="label">{{ __('Returned By') }}</p>
                            <select name="returned_by" id="" class="w-1/2 select select2">
                                <option value="" disabled>{{ __('Select User') }}</option>
                                @foreach (App\Models\User::all() as $user)
                                    <option value="{{ $user->id }}"
                                        @if ($user->id == $issue_lost->user_id) selected @endif>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('returned_by')" />
                        </div>
                        {{-- Fine --}}
                        <div id="fine-field" class="space-y-2">
                            <p class="label">{{ __('Fine') }}</p>
                            <label class="input flex items-center gap-2">
                                <input type="number" name="fine_amount" value="{{ old('fine_amount') }}"
                                    step="0.01" min="0" class="flex-1" placeholder="Enter fine amount" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('fine_amount')" />
                        </div>
                        {{-- status --}}
                        <div class="space-y-2" id="status-field">
                            <p class="label">{{ __('Fine Status') }}</p>
                            <select name="fine_status" id="fine_status" class="select  block w-full">
                                <option value="" selected>{{ __('Select Status') }}</option>
                                @foreach (app\Models\BookIssues::fineStatusList() as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('fine_status', $issue_lost->fine_status) }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->first('fine_status')" />
                        </div>
                    </div>
                    {{-- Notes --}}
                    <div class="space-y-2 ">
                        <p class="label pt-2.5">{{ __('Notes') }}</p>
                        <textarea name="notes" rows="4" placeholder="Notes" class="textarea !px-3 no-ckeditor5">{{ old('notes') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                    </div>
                    <div class="flex justify-end mt-5">
                        <x-admin.primary-button>{{ __('Submit') }}</x-admin.primary-button>
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
