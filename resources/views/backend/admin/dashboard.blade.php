<x-admin::layout>
    <x-slot name="title">Admin Dashboard</x-slot>
    <x-slot name="breadcrumb">Dashboard</x-slot>
    <x-slot name="page_slug">admin-dashboard</x-slot>

    <section>
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6"
            x-transition:enter="transition-all duration-500" x-transition:enter-start="opacity-0 translate-y-8"
            x-transition:enter-end="opacity-100 translate-y-0">

            <a href="{{ route('am.admin.index', ['status' => App\Models\AuthBaseModel::statusList()[App\Models\AuthBaseModel::STATUS_ACTIVE]]) }}"
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

            <a href="{{ route('magazine.index', ['status' => App\Models\Magazine::statusList()[App\Models\Magazine::STATUS_ACTIVE]]) }}"
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


            <a href="{{ route('newspaper.index', ['status' => App\Models\NewsPaper::statusList()[App\Models\NewsPaper::STATUS_ACTIVE]]) }}"
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

            <a href="{{ route('bim.book-issues.index', ['status' => App\Models\BookIssues::statusList()[App\Models\BookIssues::STATUS_PENDING]]) }}"
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

            <a href="{{ route('bim.book-issues.index', ['status' => App\Models\BookIssues::statusList()[App\Models\BookIssues::STATUS_ISSUED]]) }}"
                class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0.4s;">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-fuchsia-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="book-check" class="w-6 h-6 text-fuchsia-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">
                        {{ number_format($book_issued) }}</h3>
                </div>
                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">{{ __('Book Issues') }}</p>
            </a>

            <a href="{{ route('bim.book-issues.index', ['status' => App\Models\BookIssues::statusList()[App\Models\BookIssues::STATUS_OVERDUE]]) }}"
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


            <a href="{{ route('bim.book-issues.index', ['status' => App\Models\BookIssues::statusList()[App\Models\BookIssues::STATUS_LOST]]) }}"
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

            <a href="{{ route('bm.publisher.index', ['status' => App\Models\Publisher::statusList()[App\Models\Publisher::STATUS_ACTIVE]]) }}"
                class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0.2s;">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="rss" class="w-6 h-6 text-purple-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">
                        {{ number_format($publishers) }}</h3>
                </div>

                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">{{ __('Publishers') }}</p>
            </a>

            <a href="{{ route('bm.category.index', ['status' => App\Models\Category::statusList()[App\Models\Category::STATUS_ACTIVE]]) }}"
                class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0.4s;">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-zinc-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="chart-bar-stacked" class="w-6 h-6 text-zinc-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">
                        {{ number_format($categories) }}</h3>
                </div>
                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">{{ __('Categories') }}</p>
            </a>

            <a href="{{ route('bm.rack.index') }}"
                class="glass-card rounded-2xl p-6 card-hover float interactive-card" style="animation-delay: 0.6s;">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-cyan-500/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="box" class="w-6 h-6 text-cyan-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-text-white mb-1">
                        {{ number_format($racks) }}</h3>
                </div>

                <p class="text-gray-800/60 dark:text-text-dark-primary text-sm">{{ __('Racks') }}</p>
            </a>






            <a href="{{ route('bim.book-issues.index', ['fine_status' => App\Models\BookIssues::fineStatusList()[App\Models\BookIssues::FINE_UNPAID]]) }}"
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

        <!-- Charts Section -->
        {{-- <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-transition:enter="transition-all duration-500 delay-200"
            x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">

            <!-- Main Chart -->
            <div class="lg:col-span-2 glass-card rounded-2xl p-6 card-hover">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-text-white mb-1">Revenue Analytics</h3>
                        <p class="text-text-dark-primary text-sm">Monthly revenue breakdown</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <select
                            class="bg-white/10 text-text-white text-sm px-3 py-2 rounded-lg border border-white/20 outline-none">
                            <option value="monthly">Monthly</option>
                            <option value="weekly">Weekly</option>
                            <option value="daily">Daily</option>
                        </select>
                        <button
                            class="btn-primary text-text-white text-sm px-4 py-2 rounded-xl flex items-center gap-2">
                            <i data-lucide="download" class="w-4 h-4"></i>
                            Export
                        </button>
                    </div>
                </div>
                <div class="h-64 relative">
                    <canvas id="revenueChart" class="w-full h-full"></canvas>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="space-y-6">
                <!-- Recent Activity -->
                <div class="glass-card rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-text-white">Recent Activity</h3>
                        <button class="text-text-dark-primary hover:text-text-white transition-colors">
                            <i data-lucide="more-horizontal" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <div class="space-y-4">
                        <template x-for="activity in recentActivity" :key="activity.id">
                            <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 transition-colors">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                                    :class="activity.iconBg">
                                    <i :data-lucide="activity.icon" class="w-4 h-4" :class="activity.iconColor"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-text-white text-sm font-medium" x-text="activity.title"></p>
                                    <p class="text-text-dark-primary text-xs" x-text="activity.time"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="glass-card rounded-2xl p-6">
                    <h3 class="text-lg font-bold text-text-white mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <button
                            class="btn-primary p-3 rounded-xl text-text-white text-sm font-medium flex items-center justify-center gap-2 hover:scale-105 transition-transform">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            Add User
                        </button>
                        <button
                            class="bg-white/10 hover:bg-white/20 p-3 rounded-xl text-text-white text-sm font-medium flex items-center justify-center gap-2 border border-white/20 hover:scale-105 transition-all">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                            Send Mail
                        </button>
                        <button
                            class="bg-white/10 hover:bg-white/20 p-3 rounded-xl text-text-white text-sm font-medium flex items-center justify-center gap-2 border border-white/20 hover:scale-105 transition-all">
                            <i data-lucide="file-text" class="w-4 h-4"></i>
                            Reports
                        </button>
                        <button
                            class="bg-white/10 hover:bg-white/20 p-3 rounded-xl text-text-white text-sm font-medium flex items-center justify-center gap-2 border border-white/20 hover:scale-105 transition-all">
                            <i data-lucide="settings" class="w-4 h-4"></i>
                            Settings
                        </button>
                    </div>
                </div>
            </div>
        </div> --}}
    </section>
</x-admin::layout>
