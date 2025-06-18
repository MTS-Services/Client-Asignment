<x-user::layout>
    <x-slot name="title">{{ __('Book Issues List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Book Issues List') }}</x-slot>
    <x-slot name="page_slug">book_issues_{{ request('status') }}</x-slot>
    <section>
        <div class="glass-card rounded-2xl py-6">
            <div class="w-full">
                <!-- Header Section -->
                <div class="flex items-center justify-between">
                    <div class="mb-2 ps-6">
                        <h1 class="text-base md:text-lg xl:text-xl font-bold text-gray-800 dark:text-white mb-2">
                            {{ $book_issue->book?->title }}
                        </h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Published on') }} {{ $book_issue->created_at_formatted }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2 pe-6">
                        <x-user.primary-link href="{{ route('user.book-issues-list', [encrypt($book_issue->id), 'status' => request('status')]) }}">{{ __('Back') }} <i
                                data-lucide="undo-2" class="w-4 h-4"></i>
                        </x-user.primary-link>
                    </div>
                </div>

                <div class="h-px bg-gray-300 dark:bg-gray-700 mb-6"></div>

                <!-- Details Card -->
                <div class="px-6">
                    <p class="text-text-light-primary dark:text-text-dark-primary">{{ $book_issue->notes }}</p>
                </div>
            </div>

        </div>
    </section>
</x-user::layout>
