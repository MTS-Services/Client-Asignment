<x-admin::layout>
    <x-slot name="title">{{ __('Author List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Author List') }}</x-slot>
    <x-slot name="page_slug">author</x-slot>
    <section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Author List') }}</h2>
                <div class="flex items-center gap-2">
                    <x-admin.primary-link secondary="true" href="{{ route('bm.author.trash') }}">{{ __('Trash') }} <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </x-admin.primary-link>
                    <x-admin.primary-link href="{{ route('bm.author.create') }}">{{ __('Add') }} <i data-lucide="plus" class="w-4 h-4"></i>
                    </x-admin.primary-link>
                </div>
            </div>
        </div>
        <div class="glass-card rounded-2xl p-6">
            <table class="table datatable table-zebra">
                <thead>
                    <tr>
                        <th width="5%">{{ __('SL') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Nationality') }}</th>
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

    {{-- Details Modal --}}
    <x-admin.details-modal />

    @push('js')
        <script src="{{ asset('assets/js/datatable.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                let table_columns = [
                    //name and data, orderable, searchable
                    ['name', true, true],
                    ['nationality', true, true],
                    ['status', true, true],
                    ['created_by', true, true],
                    ['created_at', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('bm.author.index') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3, 4, 5],
                    model: 'Author',
                };
                // initializeDataTable(details);

                initializeDataTable(details);
            })
        </script>

        {{-- Details Modal --}}
        <script>
            document.addEventListener('DOMContentLoaded', () => {

                $(document).on('click', '.view', function() {
                    const id = $(this).data('id');
                    const route = "{{ route('bm.author.show', ':id') }}";

                    const details = [{
                            label: '{{ __('Name') }}',
                            key: 'name',
                        },
                        {
                            label: '{{ __('Status') }}',
                            key: 'status_label',
                            label_color: 'status_color',
                            type: 'status',
                        },
                        {
                            label: '{{ __('Nationality') }}',
                            key: 'nationality',
                        },
                        {
                            label: '{{ __('Birth Date') }}',
                            key: 'birth_date',
                        },
                        {
                            label: '{{ __('Death Date') }}',
                            key: 'death_date',
                        },
                        {
                            label: '{{ __('Biography') }}',
                            key: 'biography',
                        },
                        {
                            label: '{{ __('Image') }}',
                            key: 'modified_image',
                            type: 'image',
                        },
                    ];

                    showDetailsModal(route, id, '{{ __('Author Details') }}', details);
                });
            });
        </script>
    @endpush
</x-admin::layout>
