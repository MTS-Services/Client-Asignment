<header class="bg-bg-dark-active">
    <nav class="fixed top-0 left-0 right-0 z-50  shadow-md">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <div class="navbar-start">
                <a href="{{ url('/') }}" class="text-xl font-bold">{{ __('Logo') }}</a>
            </div>
            <div class="navbar-end flex items-center gap-4">
                <x-frontend.theme-toggle />

                @if (Route::is('login'))
                    <x-nav-link href="{{ route('register') }}">
                        {{ __('Register') }}
                    </x-nav-link>
                @elseif (Route::is('register'))
                    <x-nav-link href="{{ route('login') }}">
                        {{ __('Login') }}
                    </x-nav-link>
                @endif
            </div>
        </div>
    </nav>
</header>
