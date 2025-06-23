<x-user::layout>
    <x-slot name="title">User Dashboard</x-slot>
    <x-slot name="breadcrumb">{{ __('Dashboard') }}</x-slot>
    <x-slot name="page_slug">user-dashboard</x-slot>

    <section>
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6"
            x-transition:enter="transition-all duration-500" x-transition:enter-start="opacity-0 translate-y-8"
            x-transition:enter-end="opacity-100 translate-y-0">

            {{-- <a href="{{ route('am.admin.index', ['status' => App\Models\AuthBaseModel::statusList()[App\Models\AuthBaseModel::STATUS_ACTIVE]]) }}"
                class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0s;">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="user-cog" class="w-6 h-6 text-blue-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">
                        {{ number_format($active_admins) }}</h3>
                </div>
                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">{{ __('Active Admins') }}</p>
            </a>


            <a href="{{ route('um.user.index', ['status' => App\Models\AuthBaseModel::statusList()[App\Models\AuthBaseModel::STATUS_ACTIVE]]) }}"
                class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0.2s;">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="users" class="w-6 h-6 text-green-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">
                        {{ number_format($active_users) }}</h3>
                </div>

                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">{{ __('Active Users') }}</p>
            </a>

            <a href="{{ route('bm.book.index', ['status' => App\Models\Book::statusList()[App\Models\Book::STATUS_AVAILABLE]]) }}"
                class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0.4s;">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="book-a" class="w-6 h-6 text-purple-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">
                        {{ number_format($available_books) }}</h3>
                </div>
                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">{{ __('Available Books') }}</p>
            </a>
             --}}

            <a href="{{ route('user.magazine-list', ['status' => App\Models\Magazine::statusList()[App\Models\Magazine::STATUS_ACTIVE]]) }}"
                class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0.6s;">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-yellow-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="book-open-check" class="w-6 h-6 text-yellow-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">
                        {{ number_format($available_magazines) }}</h3>
                </div>

                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">{{ __('Available Magazine') }}</p>
            </a>


            <a href="{{ route('user.newspaper-list', ['status' => App\Models\NewsPaper::statusList()[App\Models\NewsPaper::STATUS_ACTIVE]]) }}"
                class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0s;">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-orange-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="newspaper" class="w-6 h-6 text-orange-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">
                        {{ number_format($available_newspapers) }}</h3>
                </div>
                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">{{ __('Available Newspapers') }}</p>
            </a>

            <a href="{{ route('user.book-issues-list', ['status' => App\Models\BookIssues::statusList()[App\Models\BookIssues::STATUS_PENDING]]) }}"
                class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0.2s;">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-indigo-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="file-user" class="w-6 h-6 text-indigo-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">
                        {{ number_format($book_requests) }}</h3>
                </div>

                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">{{ __('Book Requests') }}</p>
            </a>

            <a href="{{ route('user.book-issues-list', ['status' => App\Models\BookIssues::statusList()[App\Models\BookIssues::STATUS_ISSUED]]) }}"
                class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0.4s;">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-fuchsia-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="book-check" class="w-6 h-6 text-fuchsia-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">
                        {{ number_format($my_books) }}</h3>
                </div>
                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">{{ __('Book Issues') }}</p>
            </a>

            <a href="{{ route('user.book-issues-list', ['status' => App\Models\BookIssues::statusList()[App\Models\BookIssues::STATUS_OVERDUE]]) }}"
                class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0.6s;">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-red-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="bring-to-front" class="w-6 h-6 text-red-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">
                        {{ number_format($book_overdues) }}</h3>
                </div>

                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">{{ __('Overdue') }}</p>
            </a>


            <a href="{{ route('user.book-issues-list', ['status' => App\Models\BookIssues::statusList()[App\Models\BookIssues::STATUS_LOST]]) }}"
                class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0s;">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-pink-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="badge-x" class="w-6 h-6 text-pink-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">
                        {{ number_format($book_lost) }}</h3>
                </div>
                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">{{ __('Book Lost') }}</p>
            </a>

            {{-- <a href="{{ route('bm.publisher.index', ['status' => App\Models\Publisher::statusList()[App\Models\Publisher::STATUS_ACTIVE]]) }}"
                class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0.2s;">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="rss" class="w-6 h-6 text-purple-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">
                        {{ number_format($publishers) }}</h3>
                </div>

                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">{{ __('Publishers') }}</p>
            </a> --}}

            {{-- <a href="{{ route('bm.category.index', ['status' => App\Models\Category::statusList()[App\Models\Category::STATUS_ACTIVE]]) }}"
                class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0.4s;">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-zinc-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="chart-bar-stacked" class="w-6 h-6 text-zinc-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">
                        {{ number_format($categories) }}</h3>
                </div>
                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">{{ __('Categories') }}</p>
            </a> --}}

            {{-- <a href="{{ route('bm.rack.index') }}" class="glass-card rounded-2xl p-6 card-hover float interactive-card"
                style="animation-delay: 0.6s;">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-cyan-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="box" class="w-6 h-6 text-cyan-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">
                        {{ number_format($racks) }}</h3>
                </div>

                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">{{ __('Racks') }}</p>
            </a> --}}






            <a href="{{ route('user.book-issues-list', ['fine_status' => App\Models\BookIssues::fineStatusList()[App\Models\BookIssues::FINE_UNPAID]]) }}"
                class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0s;">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-rose-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="hand-coins" class="w-6 h-6 text-rose-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">
                        {{ number_format($unpaid) }}</h3>
                </div>
                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">{{ __('Fine Unpaid') }}</p>
            </a>

            <div class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0.2s;">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-emerald-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="banknote" class="w-6 h-6 text-emerald-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">
                        {{ number_format($fines->sum('fine_amount'), 2) }}</h3>
                </div>

                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">{{ __('Total Fine Amount') }}</p>
            </div>

            <div class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0.4s;">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-lime-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="circle-dollar-sign" class="w-6 h-6 text-lime-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">
                        {{ number_format($fines->paid()->sum('fine_amount'), 2) }}</h3>
                </div>
                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">{{ __('Total Fine Paid') }}</p>
            </div>

            <div class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0.6s;">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-pink-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="dollar-sign" class="w-6 h-6 text-pink-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">
                        {{ number_format($fines->unpaid()->sum('fine_amount'), 2) }}</h3>
                </div>

                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">{{ __('Total Fine Unpaid') }}</p>
            </div>
        </div>
    </section>
</x-user::layout>
