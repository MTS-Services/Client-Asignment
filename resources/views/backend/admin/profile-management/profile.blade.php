<x-admin::layout>
    <x-slot name="title">Profile</x-slot>
    <x-slot name="breadcrumb">Profile</x-slot>
    <x-slot name="page_slug">profile</x-slot>
    <section>
        <div class="card-glass rounded-xl shadow-sm overflow-hidden">
            <!-- Profile Header -->
            <div class="glass-card px-8 py-12">
                <div class="flex items-center space-x-6">
                    <div class="relative">
                        <div class="w-24 h-24 rounded-full flex items-center justify-center overflow-hidden">
                            <img src="{{ $admin->modified_image }}" alt="{{ $admin->name }}"
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
                        <h2 class="text-3xl font-bold">{{ $admin->name ?? 'admin Name' }}</h2>
                        <p class="text-mint-100 text-lg">{{ $admin->username ?? 'Username' }}</p>
                        <p class="text-mint-200 text-sm mt-1">Member since
                            {{ $admin->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <form action="{{ route('update-profile', encrypt($admin->id)) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <input type="file" name="image" class=" w-full  filepond" id="image"
                                accept="image/jpeg, image/png, image/jpg, image/webp, image/svg">
                            <x-input-error class="mt-2" :messages="$errors->get('image')" />
                        </div>
                        <div class="flex flex-col gap-4">
                            <div class="space-y-2">
                                <p class="label">{{ __('Name') }}</p>
                                <label class="input flex items-center gap-2">

                                    <input type="text" name="name" value="{{ $admin?->name }}" placeholder="Name"
                                        class="flex-1" />
                                </label>
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>
                            <div class="space-y-2">
                                <p class="label">{{ __('Username') }}</p>
                                <label class="input flex items-center gap-2">

                                    <input type="text" name="username" value="{{ $admin?->username }}"
                                        placeholder="Username" class="flex-1" />
                                </label>
                                <x-input-error class="mt-2" :messages="$errors->get('username')" />
                            </div>
                            <div class="space-y-2 mb-2">
                                <p class="label">{{ __('Email') }}</p>
                                <label class="input flex items-center gap-2">

                                    <input type="text" name="email" value="{{ $admin?->email }}"
                                        placeholder="Email" class="flex-1" />
                                </label>
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <x-admin.primary-button>{{ __('Update') }}</x-admin.primary-button>
                    </div>
                </form>
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
                    "#image": "{{ $admin->modified_image }}"
                });
            });
        </script>
    @endpush
</x-admin::layout>
