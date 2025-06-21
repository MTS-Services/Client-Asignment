<aside class="transition-all duration-300 ease-in-out z-50 max-h-screen py-2 pl-2"
    :class="{
        // 'relative': desktop,
        'w-72': desktop && sidebar_expanded,
        'w-20': desktop && !sidebar_expanded,
        'fixed top-0 left-0 h-full': !desktop,
        'w-72 translate-x-0': !desktop && mobile_menu_open,
        'w-72 -translate-x-full': !desktop && !mobile_menu_open,
    }">

    <div class="sidebar-glass-card h-full custom-scrollbar rounded-xl overflow-y-auto">
        <!-- Sidebar Header -->
        <a href="{{ route('admin.dashboard') }}" class="p-4 border-b border-white/10 inline-block">
            <div class="flex items-center gap-4">
                <div
                    class="w-10 h-10 glass-card shadow inset-shadow-lg bg-bg-white dark:bg-bg-black p-0 rounded-xl flex items-center justify-center overflow-hidden">
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
                        {{ env('APP_SORT_NAME', 'Admin Portal') }}</h1>
                    <p class="text-text-light-secondary dark:text-text-dark-primary text-sm">{{ __('Admin Dashboard') }}
                    </p>
                </div>
            </div>
        </a>



        <!-- Navigation Menu -->
        <nav class="p-2 space-y-2 ">
            <!-- Dashboard -->

            {{-- 1. SINGLE NAVLINK (replaces your original single-navlink) --}}
            <x-admin.navlink type="single" icon="layout-dashboard" name="Dashboard" :route="route('admin.dashboard')"
                active="admin-dashboard" :page_slug="$active" />

            {{-- 2. SIMPLE DROPDOWN (multiple items under one parent) --}}

            <x-admin.navlink type="dropdown" icon="users" name="Admin Management" :page_slug="$active"
                :items="[
                    [
                        'name' => 'Admin',
                        'route' => route('am.admin.index'),
                        'icon' => 'user',
                        'active' => 'admin',
                        'permission' => 'admin-list',
                    ],
                    [
                        'name' => 'Role',
                        'route' => route('am.role.index'),
                        'icon' => 'shield',
                        'active' => 'role',
                        'permission' => 'role-list',
                    ],
                    // [
                    //     'name' => 'Permission',
                    //     'route' => route('am.permission.index'),
                    //     'icon' => 'shield-check',
                    //     'active' => 'permission',
                    //     'permission' => 'permission-list',
                    // ],
                ]" />

            <x-admin.navlink type="dropdown" icon="users" name="User Management" :page_slug="$active"
                :items="[
                    [
                        'name' => 'User',
                        'route' => route('um.user.index'),
                        'icon' => 'user',
                        'active' => 'user',
                        'permission' => 'user-list',
                    ],
                    [
                        'name' => 'Queries',
                        'route' => route('um.query.index'),
                        'icon' => 'message-circle-more',
                        'active' => 'query-list',
                        'permission' => 'query-list',
                    ],
                ]" />
            <x-admin.navlink type="dropdown" icon="book-open-text" name="Book Management" :page_slug="$active"
                :items="[
                    [
                        'name' => 'Book',
                        'route' => route('bm.book.index'),
                        'icon' => 'book-text',
                        'active' => 'book',
                        'permission' => 'book-list',
                    ],
                    [
                        'name' => 'Category',
                        'route' => route('bm.category.index'),
                        'icon' => 'book-lock',
                        'active' => 'category',
                        'permission' => 'category-list',
                    ],
                    [
                        'name' => 'Author',
                        'route' => route('bm.author.index'),
                        'icon' => 'shield-user',
                        'active' => 'author',
                        'permission' => 'author-list',
                    ],
                    [
                        'name' => 'Publisher',
                        'route' => route('bm.publisher.index'),
                        'icon' => 'book-user',
                        'active' => 'publisher',
                        'permission' => 'publisher-list',
                    ],
                    [
                        'name' => 'Rack',
                        'route' => route('bm.rack.index'),
                        'icon' => 'layers',
                        'active' => 'rack',
                        'permission' => 'rack-list',
                    ],
                ]" />

            <x-admin.navlink type="dropdown" icon="book-marked" name="Book Issues" :page_slug="$active"
                :items="[
                    [
                        'name' => 'Book Issue Requests',
                        'route' => route('bim.book-issues.index', [
                            'status' => App\Models\BookIssues::statusList()[App\Models\BookIssues::STATUS_PENDING],
                        ]),
                        'icon' => 'book',
                        'active' =>
                            'book_issues_' . App\Models\BookIssues::statusList()[App\Models\BookIssues::STATUS_PENDING],
                        'permission' => 'book-issue-list-pending',
                    ],
                    [
                        'name' => 'Book Issued',
                        'route' => route('bim.book-issues.index', [
                            'status' => App\Models\BookIssues::statusList()[App\Models\BookIssues::STATUS_ISSUED],
                        ]),
                        'icon' => 'book-lock',
                        'active' =>
                            'book_issues_' . App\Models\BookIssues::statusList()[App\Models\BookIssues::STATUS_ISSUED],
                        'permission' => 'book-issue-list-issued',
                    ],
                    [
                        'name' => 'Book Overdue',
                        'route' => route('bim.book-issues.index', [
                            'status' => App\Models\BookIssues::statusList()[App\Models\BookIssues::STATUS_OVERDUE],
                        ]),
                        'icon' => 'book-dashed',
                        'active' =>
                            'book_issues_' . App\Models\BookIssues::statusList()[App\Models\BookIssues::STATUS_OVERDUE],
                        'permission' => 'book-issue-list-overdue',
                    ],
                    [
                        'name' => 'Book Returned',
                        'route' => route('bim.book-issues.index', [
                            'status' => App\Models\BookIssues::statusList()[App\Models\BookIssues::STATUS_RETURNED],
                        ]),
                        'icon' => 'book-check',
                        'active' =>
                            'book_issues_' .
                            App\Models\BookIssues::statusList()[App\Models\BookIssues::STATUS_RETURNED],
                        'permission' => 'book-issue-list-returned',
                    ],
                    [
                        'name' => 'Book Lost',
                        'route' => route('bim.book-issues.index', [
                            'status' => App\Models\BookIssues::statusList()[App\Models\BookIssues::STATUS_LOST],
                        ]),
                        'icon' => 'book-x',
                        'active' =>
                            'book_issues_' . App\Models\BookIssues::statusList()[App\Models\BookIssues::STATUS_LOST],
                        'permission' => 'book-issue-list-lost',
                    ],
                    [
                        'name' => 'Fine Unpaid',
                        'route' => route('bim.book-issues.index', [
                            'fine-status' => App\Models\BookIssues::fineStatusList()[
                                App\Models\BookIssues::FINE_UNPAID
                            ],
                        ]),
                        'icon' => 'badge-dollar-sign',
                        'active' =>
                            'book_issues_' .
                            App\Models\BookIssues::fineStatusList()[App\Models\BookIssues::FINE_UNPAID],
                        'permission' => 'book-issue-list-fine-unpaid',
                    ],
                    [
                        'name' => 'Fine Paid',
                        'route' => route('bim.book-issues.index', [
                            'fine-status' => App\Models\BookIssues::fineStatusList()[App\Models\BookIssues::FINE_PAID],
                        ]),
                        'icon' => 'hand-coins',
                        'active' =>
                            'book_issues_' . App\Models\BookIssues::fineStatusList()[App\Models\BookIssues::FINE_PAID],
                        'permission' => 'book-issue-list-fine-paid',
                    ],
                ]" />

            <x-admin.navlink type="single" icon="notebook-text" name="Magazine" :route="route('magazine.index')" active="magazine"
                :page_slug="$active" permission="magazine-list" />

            <x-admin.navlink type="single" icon="newspaper" name="Newspaper" :route="route('newspaper.index')" active="newspaper"
                :page_slug="$active" permission="newspaper-list" />

            <x-admin.navlink icon="settings" name="Settings" :page_slug="$active" :items="[
                [
                    'name' => 'General Settings',
                    'route' => route('app-settings.general'),
                    'icon' => 'sliders',
                    'active' => 'app-general-settings',
                    'permission' => 'application-setting-general',
                ],
                [
                    'name' => 'Database Settings',
                    'route' => route('app-settings.database'),
                    'icon' => 'database',
                    'active' => 'app-database-settings',
                    'permission' => 'application-setting-database',
                ],
                [
                    'name' => 'Email Settings',
                    'route' => route('app-settings.smtp'),
                    'icon' => 'server',
                    'active' => 'app-smtp-settings',
                    'permission' => 'application-setting-smtp',
                ],
                // [
                //     'name' => 'Email Settings',
                //     'icon' => 'mail',
                //     'subitems' => [
                //         [
                //             'name' => 'SMTP Config',
                //             'route' => '#',
                //             'icon' => 'server',
                //             'active' => 'admin-settings-email-smtp',
                //             'permission' => 'application-setting-email',
                //         ],
                //         [
                //             'name' => 'Email Templates',
                //             'route' => '#',
                //             'icon' => 'file-text',
                //             'active' => 'admin-settings-email-templates',
                //             'permission' => 'application-setting-email-template',
                //         ],
                //     ],
                // ],
            ]" />
            {{-- @if (isset($not_use)) --}}
            {{-- 3. MIXED NAVIGATION (Single items + Dropdowns in one parent) --}}

            {{-- <x-admin.navlink type="dropdown" icon="shopping-cart" name="E-commerce" :page_slug="$active"
                :items="[
                    [
                        'type' => 'single',
                        'name' => 'Dashboard',
                        'route' => '#',
                        'icon' => 'bar-chart-3',
                        'active' => 'admin-ecommerce-dashboard',
                        'permission' => 'ecommerce-dashboard',
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
                                'permission' => 'product-list',
                            ],
                            [
                                'name' => 'Add Product',
                                'route' => '#',
                                'icon' => 'plus',
                                'active' => 'admin-products-create',
                                'permission' => 'product-create',
                            ],
                            [
                                'name' => 'Categories',
                                'route' => '#',
                                'icon' => 'tag',
                                'active' => 'admin-products-categories',
                                'permission' => 'product-category-list',
                            ],
                        ],
                    ],
                    [
                        'type' => 'single',
                        'name' => 'Inventory',
                        'route' => '#',
                        'icon' => 'warehouse',
                        'active' => 'admin-inventory-index',
                        'permission' => 'inventory-list',
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
                                'permission' => 'order-list',
                            ],
                            [
                                'name' => 'Pending Orders',
                                'route' => '#',
                                'icon' => 'clock',
                                'active' => 'admin-orders-pending',
                                'permission' => 'order-pending',
                            ],
                        ],
                    ],
                    [
                        'type' => 'single',
                        'name' => 'Reports',
                        'route' => '#',
                        'icon' => 'file-text',
                        'active' => 'admin-ecommerce-reports',
                        'permission' => 'ecommerce-reports',
                    ],
                ]" /> --}}

            {{-- Mixed Dropdown (Single + Multi items in same dropdown) --}}

            {{-- Using with Boxicons instead of Lucide --}}
            {{-- <x-admin.navlink icon="monitor-cog" name="System" :page_slug="$active" :items="[
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

            {{-- <x-admin.navlink type="single" icon="help-circle" name="Help &
                    Support"
                :page_slug="$active" /> --}}
            {{-- @endif --}}

        </nav>
    </div>
</aside>
