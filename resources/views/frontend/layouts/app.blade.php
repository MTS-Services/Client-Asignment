<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('storage/' . env('FAVICON')) }}" type="image/x-icon">

    <title>
        {{ isset($title) ? $title . ' - ' : '' }}
        {{ config('app.name', 'Ecommerce') }}
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
    <script src="{{ asset('assets/js/theme-toggle.js') }}"></script>

    {{-- End theme selector && Theme store --}}

    <script src="{{ asset('assets/frontend/js/jquery.js') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])


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

    @stack('css')
    {{--
    <style>
        .bg-animated {
            background-color: hsla(0, 100%, 50%, 1);
            background-image:
                radial-gradient(at 40% 20%, hsla(28, 100%, 74%, 1) 0px, transparent 50%),
                radial-gradient(at 80% 0%, hsla(189, 100%, 56%, 1) 0px, transparent 50%),
                radial-gradient(at 0% 50%, hsla(355, 100%, 93%, 1) 0px, transparent 50%),
                radial-gradient(at 80% 50%, hsla(340, 100%, 76%, 1) 0px, transparent 50%),
                radial-gradient(at 0% 100%, hsla(22, 100%, 77%, 1) 0px, transparent 50%),
                radial-gradient(at 80% 100%, hsla(242, 100%, 70%, 1) 0px, transparent 50%),
                radial-gradient(at 0% 0%, hsla(343, 100%, 76%, 1) 0px, transparent 50%);
        }

        .gradientBG: gradientBG 15s ease infinite;

        @keyframes gradientBG {
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
    </style> --}}

</head>

<body>

    <x-frontend.enquiry />

    <main>
        {{ $slot }}
    </main>


    {{-- <x-frontend::footer :page="$page_slug" /> --}}

    <script src="{{ asset('assets/js/lucide-icon.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
    @stack('js')
</body>

</html>
