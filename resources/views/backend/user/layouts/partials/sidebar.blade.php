<aside class="transition-all duration-300 ease-in-out z-50 max-h-screen py-2 pl-2"
    :class="{
        // 'relative': desktop,
        'w-72': desktop && sidebar_expanded,
        'w-20': desktop && !sidebar_expanded,
        'fixed top-0 left-0 h-full': !desktop,
        'w-72 translate-x-0': !desktop && mobile_menu_open,
        'w-72 -translate-x-full': !desktop && !mobile_menu_open,
    }">

    <div class="sidebar-glass-card h-full custom-scrollbar overflow-y-auto">
        <!-- Sidebar Header -->
        <a href="{{ route('user.dashboard') }}" class="p-3 border-b border-white/10 inline-block">
            <div class="flex items-center gap-4">
                <div
                    class="w-10 h-10 glass-card shadow inset-shadow-lg bg-bg-white dark:bg-bg-black p-0 rounded-xl flex items-center justify-center">
                    @if (env('APP_LOGO'))
                        <img src="{{ storage_url(env('APP_LOGO')) }}" alt="{{ env('APP_NAME') }}" class="w-full h-full">
                    @else
                        <i data-lucide="zap" class="!w-4 !h-4"></i>
                    @endif
                </div>
                <div x-show="(desktop && sidebar_expanded) || (!desktop && mobile_menu_open)"
                    x-transition:enter="transition-all duration-300 delay-75"
                    x-transition:enter-start="opacity-0 translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition-all duration-200"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 -translate-x-4">
                    <h1 class="text-xl font-bold text-text-light-primary dark:text-text-white">
                        {{ env('APP_SORT_NAME', 'Student Portal') }}</h1>
                    <p class="text-text-light-secondary dark:text-text-dark-primary text-sm">{{ __('Student Portal') }}
                </div>
            </div>
        </a>
        <!-- Navigation Menu -->
        <nav class="p-2 space-y-2">
            <!-- Dashboard -->
            {{-- 1. SINGLE NAVLINK (replaces your original single-navlink) --}}
            <x-user.navlink type="single" icon="layout-dashboard" name="Dashboard" :route="route('user.dashboard')"
                active="user-dashboard" :page_slug="$active" />

            {{-- 2. SIMPLE DROPDOWN (multiple items under one parent) --}}

            <x-user.navlink type="single" icon="notebook-pen" name="Magazine" :route="route('user.magazine-list')" active="magazine"
                :page_slug="$active" />
            <x-user.navlink type="single" icon="newspaper" name="Newspaper" :route="route('user.newspaper-list')" active="newspaper"
                :page_slug="$active" />
            <x-admin.navlink type="dropdown" icon="app-window" name="Book Issues" :page_slug="$active" :items="[
                [
                    'name' => 'Book Issue Requests',
                    'route' => route('user.book-issues-list', [
                        'status' => App\Models\BookIssues::statusList()[App\Models\BookIssues::STATUS_PENDING],
                    ]),
                    'icon' => 'tags',
                    'active' =>
                        'book_issues_' . App\Models\BookIssues::statusList()[App\Models\BookIssues::STATUS_PENDING],
                ],
                [
                    'name' => 'Book Issued',
                    'route' => route('user.book-issues-list', [
                        'status' => App\Models\BookIssues::statusList()[App\Models\BookIssues::STATUS_ISSUED],
                    ]),
                    'icon' => 'tags',
                    'active' =>
                        'book_issues_' . App\Models\BookIssues::statusList()[App\Models\BookIssues::STATUS_ISSUED],
                ],
                [
                    'name' => 'Book Returned',
                    'route' => route('user.book-issues-list', [
                        'status' => App\Models\BookIssues::statusList()[App\Models\BookIssues::STATUS_RETURNED],
                    ]),
                    'icon' => 'tags',
                    'active' =>
                        'book_issues_' . App\Models\BookIssues::statusList()[App\Models\BookIssues::STATUS_RETURNED],
                ],
                [
                    'name' => 'Book Overdue',
                    'route' => route('user.book-issues-list', [
                        'status' => App\Models\BookIssues::statusList()[App\Models\BookIssues::STATUS_OVERDUE],
                    ]),
                    'icon' => 'tags',
                    'active' =>
                        'book_issues_' . App\Models\BookIssues::statusList()[App\Models\BookIssues::STATUS_OVERDUE],
                ],
                [
                    'name' => 'Book Lost',
                    'route' => route('user.book-issues-list', [
                        'status' => App\Models\BookIssues::statusList()[App\Models\BookIssues::STATUS_LOST],
                    ]),
                    'icon' => 'tags',
                    'active' =>
                        'book_issues_' . App\Models\BookIssues::statusList()[App\Models\BookIssues::STATUS_LOST],
                ],
                [
                    'name' => 'Fine Unpaid',
                    'route' => route('user.book-issues-list', [
                        'fine-status' => App\Models\BookIssues::fineStatusList()[App\Models\BookIssues::FINE_UNPAID],
                    ]),
                    'icon' => 'hand-coins',
                    'active' =>
                        'book_issues_' . App\Models\BookIssues::fineStatusList()[App\Models\BookIssues::FINE_UNPAID],
                ],
            ]" />
            {{-- <x-user.navlink type="dropdown" icon="ratio" name="Actions" :page_slug="$active"
                :items="[
                    [
                        'name' => 'action',
                        'route' => '#',
                        'icon' => 'user',
                        'active' => 'action',
                    ],
                ]" /> --}}



            @if (isset($not_use))
                {{-- 3. MIXED NAVIGATION (Single items + Dropdowns in one parent) --}}
                {{-- <x-user.navlink type="dropdown" icon="shopping-cart" name="E-commerce" :page_slug="$active"
                :items="[
                    [
                        'type' => 'single',
                        'name' => 'Dashboard',
                        'route' => '#',
                        'icon' => 'bar-chart-3',
                        'active' => 'admin-ecommerce-dashboard',
                    ],
                    [
                        'name' => 'Products',
                        'icon' => 'package',
                        'subitems' => [
                            [
                                'name' => 'All Products',
                                'route' => '#',
                                'icon' => 'list',
                                'active' => 'admin-products-index',
                            ],
                            [
                                'name' => 'Add Product',
                                'route' => '#',
                                'icon' => 'plus',
                                'active' => 'admin-products-create',
                            ],
                            [
                                'name' => 'Categories',
                                'route' => '#',
                                'icon' => 'tag',
                                'active' => 'admin-products-categories',
                            ],
                        ],
                    ],
                    [
                        'type' => 'single',
                        'name' => 'Inventory',
                        'route' => '#',
                        'icon' => 'warehouse',
                        'active' => 'admin-inventory-index',
                    ],
                    [
                        'name' => 'Orders',
                        'icon' => 'shopping-bag',
                        'subitems' => [
                            [
                                'name' => 'All Orders',
                                'route' => '#',
                                'icon' => 'list',
                                'active' => 'admin-orders-index',
                            ],
                            [
                                'name' => 'Pending Orders',
                                'route' => '#',
                                'icon' => 'clock',
                                'active' => 'admin-orders-pending',
                            ],
                        ],
                    ],
                    [
                        'type' => 'single',
                        'name' => 'Reports',
                        'route' => '#',
                        'icon' => 'file-text',
                        'active' => 'admin-ecommerce-reports',
                    ],
                ]" /> --}}

                {{-- Mixed Dropdown (Single + Multi items in same dropdown) --}}
                {{-- <x-user.navlink icon="settings" name="Settings" :page_slug="$active" :items="[
                [
                    'name' => 'General Settings',
                    'route' => '#',
                    'icon' => 'sliders',
                    'active' => 'admin-settings-general',
                ],
                [
                    'name' => 'Email Settings',
                    'icon' => 'mail',
                    'subitems' => [
                        [
                            'name' => 'SMTP Config',
                            'route' => '#',
                            'icon' => 'server',
                            'active' => 'admin-settings-email-smtp',
                        ],
                        [
                            'name' => 'Email Templates',
                            'route' => '#',
                            'icon' => 'file-text',
                            'active' => 'admin-settings-email-templates',
                        ],
                    ],
                ],
                [
                    'name' => 'Security',
                    'route' => '#',
                    'icon' => 'lock',
                    'active' => 'admin-settings-security',
                ],
                ]" /> --}}

                {{-- Using with Boxicons instead of Lucide --}}
                {{-- <x-user.navlink icon="monitor-cog" name="System" :page_slug="$active" :items="[
                    [
                        'name' => 'Cache Management',
                        'route' => '#',
                        'icon' => 'bx bx-data',
                        'boxicon' => true,
                        'active' => 'admin-system-cache',
                    ],
                    [
                        'name' => 'Logs',
                        'route' => '#',
                        'icon' => 'bx bx-file',
                        'boxicon' => true,
                        'active' => 'admin-system-logs',
                    ],
                ]" /> --}}

                {{-- <x-user.navlink type="single" icon="help-circle" name="Help &
                    Support"
                :page_slug="$active" /> --}}
            @endif

        </nav>
    </div>
</aside>
