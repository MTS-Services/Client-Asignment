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
                        <div class="flex items-center gap-2 mb-2">
                            <i data-lucide="book-open" class="w-6 h-6 text-[#3b82f6] dark:text-white"></i>
                            <h1 class="text-base md:text-lg xl:text-xl font-bold text-gray-800 dark:text-white">
                                {{ __('Book Issue Details') }}
                            </h1>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Issue Code:-') }} {{ $book_issue->issue_code }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2 pe-6">
                        <x-user.primary-link
                            href="{{ route('user.book-issues-list', ['status' => request('status')]) }}">{{ __('Back') }}
                            <i data-lucide="undo-2" class="w-4 h-4"></i>
                        </x-user.primary-link>
                    </div>
                </div>
                <div class="h-px bg-gray-300 dark:bg-gray-700 mb-6"></div>
                <!-- Details Card -->
                <div class="px-6">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="bg-bg-white/50 dark:bg-slate-900 p-4 rounded-lg">
                            <img src="{{ $book_issue->book?->modified_image }}" alt=""
                                class="w-full h-full object-cover">
                        </div>
                        <div class="flex flex-col gap-4">
                            <div class="bg-bg-white/50 dark:bg-slate-900 p-4 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">
                                    {{ __('Category') }}</p>
                                <p class="text-base font-medium mt-1 text-gray-800 dark:text-gray-200">
                                    {{ $book_issue->book?->category?->name }}</p>
                            </div>
                            <div class="bg-bg-white/50 dark:bg-slate-900 p-4 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">Book</p>
                                <p class="text-base font-medium mt-1 text-gray-800 dark:text-gray-200">
                                    {{ $book_issue->book?->title }}
                                </p>
                            </div>
                            <div class="bg-bg-white/50 dark:bg-slate-900 p-4 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">
                                    {{ __('Isbn') }}</p>
                                <p class="text-base font-medium mt-1 text-gray-800 dark:text-gray-200">
                                    {{ $book_issue->book?->isbn }}</p>
                            </div>
                            <div class="bg-bg-white/50 dark:bg-slate-900 p-4 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">Issue By</p>
                                <p class="text-base font-medium mt-1 text-gray-800 dark:text-gray-200">
                                    {{ $book_issue->issuedBy?->name ?? '-' }}
                                </p>
                            </div>
                            <div class="bg-bg-white/50 dark:bg-slate-900 p-4 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">Issued
                                    Date</p>
                                <p class="text-base font-medium mt-1 text-gray-800 dark:text-gray-200">
                                    {{ $book_issue->issue_date }}</p>
                            </div>
                        </div>
                        @if (!empty($book_issue->return_date))
                            <div class="bg-bg-white/50 dark:bg-slate-900 p-4 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">
                                    {{ __('Return By') }}</p>
                                <p class="text-base font-medium mt-1 text-gray-800 dark:text-gray-200">
                                    {{ $book_issue->returnedBy?->name ?? '-' }}</p>
                            </div>
                        @endif
                        <div class="bg-bg-white/50 dark:bg-slate-900 p-4 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">Due Date
                            </p>
                            <p class="text-base font-medium mt-1 text-gray-800 dark:text-gray-200">
                                {{ $book_issue->due_date }}</p>
                        </div>
                        @if (!empty($book_issue->return_date))
                            <div class="bg-bg-white/50 dark:bg-slate-900 p-4 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">Return Date
                                </p>
                                <p class="text-base font-medium mt-1 text-gray-800 dark:text-gray-200">
                                    {{ $book_issue->return_date ?? '-' }}</p>
                            </div>
                        @endif
                        @if (!empty($book_issue->fine_status))
                            <div class="bg-bg-white/50 dark:bg-slate-900 p-4 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">
                                    {{ __('Status') }}</p>
                                <span
                                    class="badge mt-1 text-text-white {{ $book_issue->status_color }}">{{ $book_issue->status_label }}</span>
                            </div>
                            <div class="bg-bg-white/50 dark:bg-slate-900 p-4 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">
                                    {{ __('Fine Amount') }}</p>
                                <p class="text-base font-medium mt-1 text-gray-800 dark:text-gray-200">
                                    {{ $book_issue->fine_amount ?? '-' }}</p>
                            </div>
                            <div class="bg-bg-white/50 dark:bg-slate-900 p-4 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">
                                    {{ __('Fine status') }}</p>
                                <p class="badge mt-1 text-text-white {{ $book_issue->fine_status_color }}">
                                    {{ $book_issue->fine_status_label }}</p>
                            </div>
                        @endif

                        <div class="bg-bg-white/50 dark:bg-slate-900 p-4 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">
                                {{ __('Publisher') }}</p>
                            <p class="text-base font-medium mt-1 text-gray-800 dark:text-gray-200">
                                {{ $book_issue->book?->publisher?->name }}</p>
                        </div>
                        <div class="bg-bg-white/50 dark:bg-slate-900 p-4 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">
                                {{ __('Publish Date') }}</p>
                            <p class="text-base font-medium mt-1 text-gray-800 dark:text-gray-200">
                                {{ $book_issue->book?->publication_date }}</p>
                        </div>

                        <div class="bg-bg-white/50 dark:bg-slate-900 p-4 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">
                                {{ __('Pages') }}</p>
                            <p class="text-base font-medium mt-1 text-gray-800 dark:text-gray-200">
                                {{ $book_issue->book?->pages }}</p>
                        </div>
                        <div class="bg-bg-white/50 dark:bg-slate-900 p-4 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">
                                {{ __('Language') }}</p>
                            <p class="text-base font-medium mt-1 text-gray-800 dark:text-gray-200">
                                {{ $book_issue->book?->language }}</p>
                        </div>
                        <div class="bg-bg-white/50 dark:bg-slate-900 p-4 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">
                                {{ __('Price') }}</p>
                            <p class="text-base font-medium mt-1 text-gray-800 dark:text-gray-200">
                                ${{ number_format($book_issue->book?->price, 2) }}</p>
                        </div>
                        @if ($book_issue->notes)
                            <div class="bg-bg-white/50 dark:bg-slate-900 p-4 rounded-lg col-span-2">
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">
                                    {{ __('Notes') }}</p>
                                <p class="text-base font-medium mt-1 text-gray-800 dark:text-gray-200">
                                    {{ $book_issue->notes }}</p>
                            </div>
                        @endif
                        @if ($book_issue->book?->description)
                            <div class="bg-bg-white/50 dark:bg-slate-900 p-4 rounded-lg col-span-2">
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">
                                    {{ __('Description') }}</p>
                                <p class="text-base font-medium mt-1 text-gray-800 dark:text-gray-200">
                                    {{ $book_issue->book?->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </section>
</x-user::layout>
