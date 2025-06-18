{{-- <x-frontend::layout>

    <x-slot name="title">Home</x-slot>
    <x-slot name="page_slug">home</x-slot>

    <div class="text-[#1b1b18] dark:text-[#FDFDFC] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <div>
            <h1 class="text-4xl font-bold text-red-950 dark:text-red-50">Welcome to {{ config('app.name', 'Dashboard') }}</h1>

            <p class="mt-4 text-lg font-semibold text-center">Auth Routes</p>
            <div class="flex items-center justify-center mt-4 gap-4">
                @auth('web')
                    <a href="{{ url('/dashboard') }}" class="btn btn-accent">Dashboard</a>
                @else
                    <a href="{{ url('/login') }}" class="btn btn-neutral">Login</a>
                    <a href="{{ url('/register') }}" class="btn btn-primary">Register</a>
                @endauth
                @auth('admin')
                    <a href="{{ url('/admin/dashboard') }}" class="btn btn-info">Admin Dashboard</a>
                @else
                    <a href="{{ url('/admin/login') }}" class="btn btn-secondary">Admin Login</a>
                @endauth
            </div>
        </div>
    </div>
</x-frontend::layout> --}}

<x-frontend::layout>
    <x-slot name="title">Home</x-slot>
    <x-slot name="page_slug">home</x-slot>

    <div class="relative min-h-screen flex items-center justify-center bg-fixed bg-cover bg-center"
        style="background-image: url({{ asset('frontend/images/admin.jpg') }}); animation: bgMove 20s infinite linear;">

        <div class="absolute inset-0  backdrop-blur-sm"></div>

        <div class="relative z-10 text-slate-700 dark:text-slate-200 text-center px-6 ">
            <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold mb-6 drop-shadow-lg">
                Welcome to {{ config('app.name', 'Dashboard') }}
            </h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-10 items-center justify-items-center">
                <!-- Student Login Box -->
                <a href="{{ url('/login') }}"
                    class="w-full h-60 block group bg-gray-400 hover:bg-white/50 text-white hover:text-black backdrop-blur-lg border border-white/30 shadow-xl rounded-xl transition transform hover:-translate-y-1 duration-300">
                    <div class="flex flex-col items-center justify-center text-center h-full">
                        <i data-lucide="user" class="w-12 h-12 mb-4"></i>
                        <h2 class="text-xl font-bold mb-2">Continue as Student</h2>
                        <p>Login to your student account</p>
                    </div>
                </a>

                <!-- Admin Login Box -->
                <a href="{{ url('/admin/login') }}"
                    class="w-full h-60 group bg-gray-400 hover:bg-white/50 text-white hover:text-black backdrop-blur-lg border border-white/30 shadow-xl rounded-xl transition transform hover:-translate-y-1 duration-300">
                    <div class="flex flex-col items-center justify-center text-center h-full">
                        <i data-lucide="user-cog" class="w-12 h-12 mb-4"></i>
                        <h2 class="text-xl font-bold text-white group-hover:text-black mb-2">Continue as Admin</h2>
                        <p class="text-white/80 group-hover:text-black">Login to admin dashboard</p>
                    </div>
                </a>
            </div>

        </div>
    </div>

    <style>
        @keyframes bgMove {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }
    </style>
</x-frontend::layout>
