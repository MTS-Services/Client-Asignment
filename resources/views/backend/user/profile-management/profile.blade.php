<x-user::layout>
    <x-slot name="title">Profile</x-slot>
    <x-slot name="breadcrumb">Profile</x-slot>
    <x-slot name="page_slug">profile</x-slot>
    <section>
        <div class="card-glass p-2">
            <div class="">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Profile Header -->
                    <div class="glass-card px-8 py-12 text-text-black dark:text-text-white">
                        <div class="flex items-center space-x-6">
                            <div class="relative">
                                <div class="w-24 h-24 rounded-full flex items-center justify-center overflow-hidden">
                                    <img src="{{ auth()->user()->modified_image }}" alt="{{ auth()->user()->name }}"
                                        class="w-full h-full object-cover">
                                </div>
                                {{-- <button
                                    class="absolute -bottom-2 -right-2 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-shadow">
                                    <svg class="w-4 h-4 text-mint-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </button> --}}
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold">{{ auth()->user()->name ?? 'User Name' }}</h2>
                                <p class="text-mint-100 text-lg">{{ auth()->user()->username ?? 'Username' }}</p>
                                <p class="text-mint-200 text-sm mt-1">Member since
                                    {{ $user->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="glass-card p-6">
                        <form action="{{ route('user.update-profile', encrypt($user->id)) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <input type="file" name="image" class=" w-full  filepond" id="image"
                                        accept="image/jpeg, image/png, image/jpg, image/webp, image/svg">
                                    <x-input-error class="mt-2" :messages="$errors->get('image')" />
                                </div>
                                <div class="flex flex-col gap-4">
                                    <div>
                                        <label class="block pb-2">{{ __('Name') }} <span
                                                class="text-text-active">*</span></label>
                                        <input type="text" name="name" value="{{ $user?->name }}" class="input "
                                            placeholder="Enter your full name">
                                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                    </div>
                                    <div>
                                        <label class="block pb-2">{{ __('Username') }} <span
                                                class="text-text-active">*</span></label>
                                        <input type="text" name="username" value="{{ $user?->username }}"
                                            class="input" placeholder="Enter your username">
                                        <x-input-error class="mt-2" :messages="$errors->get('username')" />
                                    </div>
                                     <div>
                                    <label class="block pb-2">{{ __('Email') }} <span
                                            class="text-text-active">*</span></label>
                                    <input type="text" name="email" value="{{ $user?->email }}" class="input "
                                        placeholder="Enter your email">
                                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="btn btn-primary mt-5 bg-text-active dark:bg-bg-dark-muted dark:text-text-white dark:hover:bg-bg-dark-tertiary text-text-light-primary border-0 hover:bg-bg-white">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @push('js')
        {{-- filepond --}}
        <script src="{{ asset('assets/js/filepond.js') }}"></script>
        <script src="{{ asset('assets/js/filepond-plugin-image-preview.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                file_upload(["#image"], ["image/jpeg", "image/png", "image/jpg, image/webp, image/svg"], {
                    "#image": "{{ $user->modified_image }}"
                });
            });
        </script>
    @endpush
</x-user::layout>
