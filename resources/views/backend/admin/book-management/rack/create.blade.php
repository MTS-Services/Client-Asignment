<x-admin::layout>
    <x-slot name="title">{{ __('Create Rack') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Create Rack') }}</x-slot>
    <x-slot name="page_slug">rack</x-slot>

    @push('css')
        {{-- <link rel="stylesheet" href="{{ asset('assets/css/filepond.css') }}"> --}}
    @endpush

    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Create Rack') }}</h2>
                <x-admin.primary-link href="{{ route('bm.rack.index') }}">{{ __('Back') }} </x-admin.primary-link>
            </div>
        </div>

        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-1  {{ isset($documentation) && $documentation ? 'md:grid-cols-7' : '' }}">
            <!-- Form Section -->
            <div class="glass-card rounded-2xl p-6 md:col-span-5">
                <form action="{{ route('bm.rack.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <!-- Number of Rack -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Number of Rack') }}</p>
                            <label class="input flex items-center gap-2">

                                <input type="text" placeholder="Number of Rack" value="{{ old('rack_number') }}"
                                    name="rack_number" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('rack_number')" />
                        </div>
                        <!-- Location -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Location') }}</p>
                            <label class="input flex items-center gap-2">

                                <input type="text" placeholder="Location" value="{{ old('location') }}"
                                    name="location" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('location')" />
                        </div>
                        <!-- Capacity -->
                        <div class="space-y-2">
                            <p class="label">{{ __('Capacity') }}</p>
                            <label class="input flex items-center gap-2">

                                <input type="text" placeholder="Capacity" value="{{ old('capacity') }}"
                                    name="capacity" class="flex-1" />
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('capacity')" />
                        </div>

                        {{-- Description --}}
                        <div class="space-y-2 sm:col-span-2">
                            <p class="label">{{ __('Description') }}</p>
                            <textarea name="description" rows="4" placeholder="Description" class="textarea">{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>
                    </div>
                    <div class="flex justify-end mt-5">
                        <x-admin.primary-button>{{ __('Create') }}</x-admin.primary-button>
                    </div>
                </form>
            </div>

            {{-- documentation will be loded here and add md:col-span-2 class --}}

        </div>
    </section>
    @push('js')
        <script src="{{ asset('assets/js/ckEditor.js') }}"></script>
    @endpush
</x-admin::layout>
