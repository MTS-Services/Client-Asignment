<x-admin::layout>
    <x-slot name="title">{{ __('Magazine List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Magazine List') }}</x-slot>
    <x-slot name="page_slug">magazine</x-slot>
    <section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Magazine List') }}</h2>
                <div class="flex items-center gap-2">
                    <x-admin.primary-link secondary="true" href="{{ route('magazine.trash') }}">{{ __('Trash') }} <i
                            data-lucide="trash-2" class="w-4 h-4"></i>
                    </x-admin.primary-link>
                    <x-admin.primary-link href="{{ route('magazine.create') }}">{{ __('Add') }} <i
                            data-lucide="plus" class="w-4 h-4"></i>
                    </x-admin.primary-link>
                </div>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6">
            <table class="table datatable table-zebra">
                <thead>
                    <tr>
                        <th width="5%">{{ __('SL') }}</th>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Slug') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Created By') }}</th>
                        <th>{{ __('Created Date') }}</th>
                        <th width="10%">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </section>

    <x-admin.details-modal />

    @push('js')
        <script src="{{ asset('assets/js/datatable.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                let table_columns = [
                    //name and data, orderable, searchable
                    ['title', true, true],
                    ['slug', true, true],
                    ['status', true, true],
                    ['created_by', true, true],
                    ['created_at', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('magazine.index') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3, 4],
                    model: 'Permission',
                };
                // initializeDataTable(details);

                initializeDataTable(details);
            })
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {

                $(document).on('click', '.view', function() {
                    const id = $(this).data('id');
                    const route = "{{ route('magazine.show', ':id') }}";

                    const details = [{
                            label: '{{ __('Title') }}',
                            key: 'title',
                        },
                        {
                            label: '{{ __('Slug') }}',
                            key: 'slug',
                            icon: 'shield-check',
                        },
                        {
                            label: '{{ __('Url') }}',
                            key: 'url',
                            icon: 'link',
                        },
                        {
                            label: '{{ __('Status') }}',
                            key: 'status_label',
                            label_color: 'status_color',
                            type: 'status',
                        },
                        {
                            label: '{{ __('Description') }}',
                            key: 'description',
                        },
                        {
                            label: '{{ __('Cover Image') }}',
                            key: 'modified_image',
                            type: 'image',
                        },
                    ];

                    showDetailsModal(route, id, '{{ __('Magazine Details') }}', details);
                });
            });
        </script>
    @endpush
</x-admin::layout>
