<x-admin::layout>
    <x-slot name="title">{{ __('Book Issues Return List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Book Issues Return List') }}</x-slot>
    <x-slot name="page_slug">book_issues</x-slot>

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

                <form action="{{ route('im.book-issues.update-return', encrypt($issue->id)) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <select name="returned_by" id=""  class="w-1/2 border-gray-300 dark:border-gray-600">
                         <option value="" disabled>{{ __('Select User') }}</option>
                        @foreach (App\Models\User::all() as $user)
                            <option value="{{ $user->id }}" @if ($user->id == $issue->user_id) selected @endif>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                     
                    <button type="submit" class="btn btn-primary">
                        {{ __('Mark as Returned') }}
                    </button>
                </form>
            </div>

            {{-- documentation will be loaded here and add md:col-span-2 class --}}

        </div>
    </section>
    @push('js')
        <script src="{{ asset('assets/js/filepond.js') }}"></script>
    @endpush
</x-admin::layout>
