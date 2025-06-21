<x-frontend::layout>
    <x-slot name="title">Home</x-slot>
    <x-slot name="page_slug">home</x-slot>

    <div
        class="relative min-h-screen overflow-hidden bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 opacity-40 dark:opacity-20">
            <div class="floating-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
                <div class="shape shape-4"></div>
                <div class="shape shape-5"></div>
                <div class="shape shape-6"></div>
            </div>
        </div>

        <!-- Subtle Overlay -->
        <div class="absolute inset-0 bg-white/10 dark:bg-black/20"></div>

        <!-- Main Content -->
        <div class="relative z-10 min-h-screen flex items-center justify-center px-6">
            <div class="max-w-6xl w-full mx-auto text-center">

                <!-- Hero Title with Animation -->
                <div class="mb-12 animate-fade-in">
                    <h1
                        class="text-4xl md:text-6xl lg:text-7xl font-black mb-6 bg-gradient-to-r from-slate-800 via-blue-700 to-indigo-800 bg-clip-text text-transparent dark:from-white dark:via-blue-100 dark:to-indigo-200 drop-shadow-sm">
                        Welcome to <span class="text-gradient">{{ config('app.name', 'Dashboard') }}</span>
                    </h1>
                    <p class="text-lg md:text-xl text-slate-600 dark:text-slate-300 max-w-2xl mx-auto leading-relaxed">
                        Access your personalized dashboard with seamless login experience
                    </p>
                </div>

                <!-- Login Cards Container -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-4xl mx-auto">

                    <!-- Student Login Card -->
                    <a href="{{ url('/login') }}" class="login-card group py-10">
                        <div class="card-glow"></div>
                        <div class="card-content">
                            <div class="icon-container mb-6">
                                <div class="icon-bg student-bg">
                                    <i data-lucide="user" class="w-8 h-8 text-white"></i>
                                </div>
                                <div class="icon-ring"></div>
                            </div>

                            <h2
                                class="text-2xl font-bold text-slate-800 dark:text-white mb-3 group-hover:text-blue-700 dark:group-hover:text-blue-300 transition-colors duration-300">
                                Student Portal
                            </h2>
                            <p
                                class="text-slate-600 dark:text-slate-300 group-hover:text-slate-700 dark:group-hover:text-slate-200 transition-colors duration-300 mb-6">
                                Access your personalized dashboard
                            </p>

                            <div class="cta-button">
                                <span>Continue as Student</span>
                                <i data-lucide="arrow-right"
                                    class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform duration-300"></i>
                            </div>
                        </div>
                    </a>

                    <!-- Admin Login Card -->
                    <a href="{{ url('/admin/login') }}" class="login-card group  py-10">
                        <div class="card-glow admin-glow"></div>
                        <div class="card-content">
                            <div class="icon-container mb-6">
                                <div class="icon-bg admin-bg">
                                    <i data-lucide="user-cog" class="w-8 h-8 text-white"></i>
                                </div>
                                <div class="icon-ring admin-ring"></div>
                            </div>

                            <h2
                                class="text-2xl font-bold text-slate-800 dark:text-white mb-3 group-hover:text-indigo-700 dark:group-hover:text-indigo-300 transition-colors duration-300">
                                Admin Dashboard
                            </h2>
                            <p
                                class="text-slate-600 dark:text-slate-300 group-hover:text-slate-700 dark:group-hover:text-slate-200 transition-colors duration-300 mb-6">
                                Access admin control panel
                            </p>

                            <div class="cta-button admin-cta">
                                <span>Continue as Admin</span>
                                <i data-lucide="arrow-right"
                                    class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform duration-300"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Additional Features Section -->
                <div class="mt-16 animate-fade-in-up">
                    <div class="flex flex-wrap justify-center gap-6 text-slate-600 dark:text-slate-300">
                        <div
                            class="flex items-center gap-2 bg-white/60 dark:bg-slate-800/60 backdrop-blur-sm rounded-full px-4 py-2 border border-blue-200 dark:border-slate-600">
                            <i data-lucide="shield-check" class="w-4 h-4 text-green-500"></i>
                            <span class="text-sm font-medium">Secure Login</span>
                        </div>
                        <div
                            class="flex items-center gap-2 bg-white/60 dark:bg-slate-800/60 backdrop-blur-sm rounded-full px-4 py-2 border border-blue-200 dark:border-slate-600">
                            <i data-lucide="zap" class="w-4 h-4 text-amber-500"></i>
                            <span class="text-sm font-medium">Lightning Fast</span>
                        </div>
                        <div
                            class="flex items-center gap-2 bg-white/60 dark:bg-slate-800/60 backdrop-blur-sm rounded-full px-4 py-2 border border-blue-200 dark:border-slate-600">
                            <i data-lucide="smartphone" class="w-4 h-4 text-blue-500"></i>
                            <span class="text-sm font-medium">Mobile Friendly</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Floating Background Shapes */



        /* Login Cards */
        /* .login-card {

            position: relative;
            display: block;
            background: rgba(255, 255, 255, 0.85);
            border-radius: 24px;
            padding: 40px 32px;
            border: 1px solid rgba(59, 130, 246, 0.1);
            backdrop-filter: blur(20px);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            overflow: hidden;
            min-height: 320px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .dark .login-card {
            background: rgba(30, 41, 59, 0.85);
            border-color: rgba(99, 102, 241, 0.2);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .login-card:hover {
            transform: translateY(-8px) scale(1.02);
            border-color: rgba(59, 130, 246, 0.3);
            box-shadow: 0 25px 50px rgba(59, 130, 246, 0.15);
        }

        .dark .login-card:hover {
            border-color: rgba(99, 102, 241, 0.4);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
        } */

        .card-glow {
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.2) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .admin-glow {
            background: radial-gradient(circle, rgba(99, 102, 241, 0.2) 0%, transparent 70%);
        }

        .login-card:hover .card-glow {
            opacity: 1;
        }

        .card-content {
            position: relative;
            z-index: 2;
        }

        /* Icon Styling */
        .icon-container {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .icon-bg {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
            transition: all 0.3s ease;
        }

        .student-bg {
            background: linear-gradient(135deg, #3b82f6, #1e40af);
        }

        .admin-bg {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
        }

        .icon-ring {
            position: absolute;
            width: 90px;
            height: 90px;
            border: 2px solid rgba(59, 130, 246, 0.5);
            border-radius: 22px;
            animation: pulse-ring 2s infinite;
        }

        .admin-ring {
            border-color: rgba(99, 102, 241, 0.5);
        }

        @keyframes pulse-ring {
            0% {
                transform: scale(1);
                opacity: 0.5;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.3;
            }

            100% {
                transform: scale(1);
                opacity: 0.5;
            }
        }

        .login-card:hover .icon-bg {
            transform: scale(1.1);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        /* CTA Button */
        .cta-button {
            display: inline-flex;
            align-items: center;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(99, 102, 241, 0.05));
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 12px;
            padding: 12px 24px;
            color: #475569;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .dark .cta-button {
            color: #e2e8f0;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(99, 102, 241, 0.1));
            border-color: rgba(59, 130, 246, 0.3);
        }

        .admin-cta {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.05));
            border-color: rgba(99, 102, 241, 0.2);
        }

        .dark .admin-cta {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(139, 92, 246, 0.1));
            border-color: rgba(99, 102, 241, 0.3);
        }

        .login-card:hover .cta-button {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(99, 102, 241, 0.1));
            border-color: rgba(59, 130, 246, 0.4);
            transform: translateY(-2px);
            color: #1e40af;
        }

        .dark .login-card:hover .cta-button {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.3), rgba(99, 102, 241, 0.2));
            border-color: rgba(59, 130, 246, 0.5);
            color: #93c5fd;
        }

        /* Animations */
        .animate-fade-in {
            animation: fadeIn 1s ease-out;
        }

        .animate-fade-in-up {
            animation: fadeInUp 1s ease-out 0.5s both;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .login-card {
                padding: 32px 24px;
                min-height: auto;
            }

            .icon-bg {
                width: 60px;
                height: 60px;
            }

            .icon-ring {
                width: 70px;
                height: 70px;
            }
        }
    </style>
</x-frontend::layout>
