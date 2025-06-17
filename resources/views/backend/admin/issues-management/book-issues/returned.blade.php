<x-admin::layout>
    <x-slot name="title">{{ __('Book Issues Return List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Book Issues Return List') }}</x-slot>
    <x-slot name="page_slug">book_issues_{{ request('status') }}</x-slot>

    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Return Book Issue') }}</h2>
                <x-admin.primary-link
                    href="{{ route('bim.book-issues.index', ['status' => request('status')]) }}">{{ __('Back') }} <i
                        data-lucide="undo-2" class="w-4 h-4"></i></x-admin.primary-link>
            </div>
        </div>
        <div class="glass-card">
            <h2 class="text-xl font-bold pl-4 pt-4">Book Issue Details</h2>
            <div class=" grid grid-cols-2 gap-4 p-4 rounded-xl mb-4 space-y-4">
                <div class="space-y-2">
                    <p><strong>User:</strong> {{ $issue->user?->name }}</p>
                    <p><strong>Book:</strong> {{ $issue->book?->title }}</p>
                    <p><strong>Issued By:</strong> {{ $issue->issuedBy?->name }}</p>
                </div>
                <div class="space-y-2">
                    <p><strong>Issued Date:</strong> {{ $issue->issue_date }}</p>
                    <p><strong>Due Date:</strong> {{ $issue->due_date }}</p>

                </div>
            </div>
        </div>
        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1 {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <!-- Form Section -->
            <div class="glass-card rounded-2xl p-6 md:col-span-5">

                <form action="{{ route('bim.book-issues.update-return', encrypt($issue->id)) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                        <div class="space-y-2">
                            <p class="label">{{ __('Returned By') }}</p>
                            <select name="returned_by" id="" class="w-1/2 select select2">
                                <option value="" disabled>{{ __('Select User') }}</option>
                                @foreach (App\Models\User::all() as $user)
                                    <option value="{{ $user->id }}"
                                        @if ($user->id == $issue->user_id) selected @endif>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Issue Date --}}
                        <div class="space-y-2">
                            <p class="label">{{ __('Return Date') }}</p>
                            <label class="input flex items-center gap-2">
                                <input type="date" name="return_date" value="{{ old('return_date') }}"
                                    class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('return_date')" />
                        </div>
                        {{-- Fine --}}
                        <div id="fine-field" class="space-y-2 hidden">
                            <p class="label">{{ __('Fine') }}</p>
                            <label class="input flex items-center gap-2">
                                <input type="number" name="fine_amount" value="{{ old('fine_amount') }}" step="0.01" min="0" class="flex-1"
                                    placeholder="Enter fine amount" />
                                    <x-input-error class="mt-2" :messages="$errors->get('fine_amount')" />
                            </label>
                        </div>
                    </div>
                      {{-- Notes --}}
                        <div class="space-y-2 ">
                            <p class="label pt-2.5">{{ __('Notes') }}</p>
                            <textarea name="notes" rows="4" placeholder="Notes" class="textarea !px-3">{{ old('notes') }}</textarea>
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
        @push('js')
            <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

            <script>
                const input = document.querySelector('input[name="return_date"]');
                const fine = document.getElementById('fine-field');
                const due = new Date("{{ $issue->due_date }}").toISOString().split('T')[0];

                input.addEventListener('change', e => {
                    const ret = new Date(e.target.value).toISOString().split('T')[0];
                    fine.classList.toggle('hidden', ret <= due || isNaN(new Date(e.target.value)));
                }); 
            </script>
        @endpush

    @endpush
</x-admin::layout>
