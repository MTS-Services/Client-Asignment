<x-user::layout>
    <x-slot name="title">{{ __('Newspaper Details') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Newspaper Details') }}</x-slot>
    <x-slot name="page_slug">newspaper</x-slot>
    <section>
        <div class="glass-card rounded-2xl py-6">
            <div class="w-full">
                <div class="flex items-center justify-between">
                    <div class="mb-2 ps-6">
                        <h1 class="text-base md:text-lg xl:text-xl font-bold text-gray-800 dark:text-white mb-2">
                            {{ $newspaper->title }}
                        </h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Published on') }} {{ $newspaper->created_at_formatted }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2 pe-6">
                        <x-user.primary-link href="{{ route('user.newspaper-list') }}">{{ __('Back') }} <i
                                data-lucide="undo-2" class="w-4 h-4"></i>
                        </x-user.primary-link>
                    </div>
                </div>
                <div class="h-px bg-gray-300 dark:bg-gray-700 mb-6"></div>

                <!-- Details Card -->
                <div class="px-6">
                    <img src="{{ $newspaper->modified_image }}" class="w-full h-[300px] object-contain" alt="">
                    <p class="text-text-light-primary dark:text-text-dark-primary">{{ $newspaper->description }}</p>
                    @if ($newspaper->url)
                        <p class="mt-5">For More Details <a class="text-primary" target="_blank"
                                href="{{ $newspaper->url }}">Click
                                Here</a>
                        </p>
                    @endif
                </div>

            </div>

        </div>
    </section>
</x-user::layout>
