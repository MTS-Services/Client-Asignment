<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('storage/' . env('FAVICON')) }}" type="image/x-icon">

    <title>
        {{ isset($title) ? $title . ' - ' : '' }}
        {{ config('app.name', 'Dashboard Setup') }}
    </title>

    <title>
        {{ isset($title) ? $title . ' - ' : '' }}
        {{ config('app.name', 'Dashboard Setup') }}
    </title>

    {{-- Theme selector && Theme store --}}
    <script>
        // On page load, immediately apply theme from localStorage to prevent flash
        (function() {
            let theme = localStorage.getItem('theme') || 'system';

            // Apply theme immediately
            if (theme === 'system') {
                const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                document.documentElement.classList.toggle('dark', systemPrefersDark);
                document.documentElement.setAttribute('data-theme', systemPrefersDark ? 'dark' : 'light');
            } else {
                document.documentElement.classList.toggle('dark', theme === 'dark');
                document.documentElement.setAttribute('data-theme', theme);
            }
        })();
    </script>
    {{-- toggle theme for system, light and Dark Mode --}}
    {{-- <script src="{{ asset('assets/js/toggle-theme-3.js') }}"></script> --}}
    {{-- Toggle theme only dark mode and light mode --}}
    <script src="{{ asset('assets/js/toggle-theme.js') }}"></script>


    {{-- BoxIcon  --}}
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    @vite(['resources/css/dashboard.css', 'resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                showAlert('success', '{{ session('success') }}');
            @endif

            @if (session('error'))
                showAlert('error', '{{ session('error') }}');
            @endif

            @if (session('warning'))
                showAlert('warning', '{{ session('warning') }}');
            @endif
        });

        const content_image_upload_url = '{{ route('file.ci_upload') }}';
    </script>
    {{-- Custom CSS  --}}
    @stack('css')

</head>

<body x-data="dashboardData()" class="animated-bg overflow-x-hidden">

    <!-- Mobile/Tablet Overlay -->
    <div x-show="mobile_menu_open && !desktop" x-transition:enter="transition-all duration-300 ease-out"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-all duration-300 ease-in" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="closeMobileMenu()" class="fixed inset-0 z-40 glass-card lg:hidden">
    </div>

    <div class="flex h-screen">
        <!-- Sidebar -->

        <x-admin::side-bar :active="$page_slug" />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col custom-scrollbar overflow-y-auto">
            <!-- Header -->

            <x-admin::header :breadcrumb="$breadcrumb" />

            <!-- Main Content Area -->
            <main class="flex-1 p-4 lg:p-6">
                <div class="mx-auto space-y-6">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <!-- Notification Panel -->
    <x-admin::notification />

    <script src="{{ asset('assets/js/lucide-icon.js') }}"></script>
    <script>
        function dashboardData() {
            return {
                // Responsive state
                desktop: window.innerWidth >= 1024,
                mobile: window.innerWidth <= 768,
                tablet: window.innerWidth < 1024,
                sidebar_expanded: window.innerWidth >= 1024,
                mobile_menu_open: false,

                // App state
                searchQuery: '',
                darkMode: true,
                showNotifications: false,

                notifications: [{
                        id: 1,
                        title: 'System Update',
                        message: 'System maintenance scheduled for tonight',
                        time: '5 minutes ago',
                        icon: 'settings',
                        iconBg: 'bg-blue-500/20',
                        iconColor: 'text-blue-400'
                    },
                    {
                        id: 2,
                        title: 'New Comment',
                        message: 'Someone commented on your post',
                        time: '10 minutes ago',
                        icon: 'message-circle',
                        iconBg: 'bg-green-500/20',
                        iconColor: 'text-green-400'
                    },
                    {
                        id: 3,
                        title: 'Security Alert',
                        message: 'New login from unknown device',
                        time: '1 hour ago',
                        icon: 'shield-alert',
                        iconBg: 'bg-red-500/20',
                        iconColor: 'text-red-400'
                    }
                ],

                // Methods
                // init() {
                //     this.handleResize();
                //     this.initChart();
                //     window.addEventListener('resize', () => this.handleResize());

                //     // Keyboard shortcuts
                //     document.addEventListener('keydown', (e) => {
                //         if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                //             e.preventDefault();
                //             this.focusSearch();
                //         }
                //     });
                // },

                handleResize() {
                    this.desktop = window.innerWidth >= 1024;
                    if (this.desktop) {
                        this.mobile_menu_open = false;
                        this.sidebar_expanded = true;
                    } else {
                        this.sidebar_expanded = false;
                    }
                },

                toggleSidebar() {
                    if (this.desktop) {
                        this.sidebar_expanded = !this.sidebar_expanded;
                    } else {
                        this.mobile_menu_open = !this.mobile_menu_open;
                    }
                },

                closeMobileMenu() {
                    if (!this.desktop) {
                        this.mobile_menu_open = false;
                    }
                },

                // setActiveTab(tab) {
                //     this.activeTab = tab;
                //     this.closeMobileMenu();

                //     // Reinitialize chart if switching to dashboard
                //     if (tab === 'dashboard') {
                //         this.$nextTick(() => {
                //             this.initChart();
                //         });
                //     }
                // },

                toggleNotifications() {
                    this.showNotifications = !this.showNotifications;
                },

                // handleSearch() {
                //     if (this.searchQuery.length > 0) {
                //         console.log('Searching for:', this.searchQuery);
                //         // Add search logic here
                //     }
                // },

                // focusSearch() {
                //     const searchInput = document.querySelector('input[type="text"]');
                //     if (searchInput) {
                //         searchInput.focus();
                //     }
                // },

                // showDetails(type) {
                //     console.log('Showing details for:', type);
                // },

                // initChart() {
                //     this.$nextTick(() => {
                //         const canvas = document.getElementById('revenueChart');
                //         if (!canvas) return;

                //         const ctx = canvas.getContext('2d');

                //         // Destroy existing chart if it exists
                //         if (window.revenueChart instanceof Chart) {
                //             window.revenueChart.destroy();
                //         }

                //         // Create gradient
                //         const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                //         gradient.addColorStop(0, 'rgba(102, 126, 234, 0.8)');
                //         gradient.addColorStop(1, 'rgba(118, 75, 162, 0.1)');

                //         window.revenueChart = new Chart(ctx, {
                //             type: 'line',
                //             data: {
                //                 labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep',
                //                     'Oct', 'Nov', 'Dec'
                //                 ],
                //                 datasets: [{
                //                     label: 'Revenue',
                //                     data: [12000, 19000, 15000, 25000, 22000, 30000, 28000, 35000,
                //                         32000, 38000, 42000, 48000
                //                     ],
                //                     borderColor: '#667eea',
                //                     backgroundColor: gradient,
                //                     borderWidth: 3,
                //                     fill: true,
                //                     tension: 0.4,
                //                     pointBackgroundColor: '#667eea',
                //                     pointBorderColor: '#ffffff',
                //                     pointBorderWidth: 2,
                //                     pointRadius: 6,
                //                     pointHoverRadius: 8
                //                 }]
                //             },
                //             options: {
                //                 responsive: true,
                //                 maintainAspectRatio: false,
                //                 plugins: {
                //                     legend: {
                //                         display: false
                //                     }
                //                 },
                //                 scales: {
                //                     x: {
                //                         grid: {
                //                             display: false
                //                         },
                //                         ticks: {
                //                             color: 'rgba(255, 255, 255, 0.6)'
                //                         }
                //                     },
                //                     y: {
                //                         grid: {
                //                             color: 'rgba(255, 255, 255, 0.1)'
                //                         },
                //                         ticks: {
                //                             color: 'rgba(255, 255, 255, 0.6)',
                //                             callback: function(value) {
                //                                 return '$' + value.toLocaleString();
                //                             }
                //                         }
                //                     }
                //                 },
                //                 interaction: {
                //                     intersect: false,
                //                     mode: 'index'
                //                 },
                //                 elements: {
                //                     point: {
                //                         hoverBackgroundColor: '#ffffff'
                //                     }
                //                 }
                //             }
                //         });
                //     });
                // }
            }
        }

        // Initialize Lucide icons after DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // if (typeof lucide !== 'undefined') {
            lucide.createIcons();
            // }
        });

        // Smooth scrolling for anchor links
        document.addEventListener('click', function(e) {
            if (e.target.matches('a[href^="#"]')) {
                e.preventDefault();
                const target = document.querySelector(e.target.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });

        // Add loading states for interactive elements
        document.addEventListener('click', function(e) {
            if (e.target.matches('.btn-primary') || e.target.closest('.btn-primary')) {
                const btn = e.target.matches('.btn-primary') ? e.target : e.target.closest('.btn-primary');
                btn.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    btn.style.transform = '';
                }, 150);
            }
        });

        // Add ripple effect to interactive cards
        document.addEventListener('click', function(e) {
            if (e.target.matches('.interactive-card') || e.target.closest('.interactive-card')) {
                const card = e.target.matches('.interactive-card') ? e.target : e.target.closest(
                    '.interactive-card');
                const rect = card.getBoundingClientRect();
                const ripple = document.createElement('span');
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;

                ripple.style.cssText = `
    position: absolute;
    width: ${size}px;
    height: ${size}px;
    left: ${x}px;
    top: ${y}px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    transform: scale(0);
    animation: ripple 0.6s ease-out;
    pointer-events: none;
    `;

                card.style.position = 'relative';
                card.style.overflow = 'hidden';
                card.appendChild(ripple);

                setTimeout(() => {
                    ripple.remove();
                }, 600);
            }
        });

        // Add CSS for ripple animation
        const style = document.createElement('style');
        style.textContent = `
    @keyframes ripple {
    to {
    transform: scale(2);
    opacity: 0;
    }
    }
    `;
        document.head.appendChild(style);
    </script>
    {{-- Custom JS --}}
    <script src="{{ asset('assets/js/details-modal.js') }}"></script>
    <script src="{{ asset('assets/js/password.js') }}"></script>
    @stack('js')

</body>

</html>
