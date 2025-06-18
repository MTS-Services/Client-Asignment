<x-admin::layout>
    <x-slot name="title">{{ __('Publisher List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Publisher List') }}</x-slot>
    <x-slot name="page_slug">publisher</x-slot>
    <section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Publisher List') }}</h2>
                <div class="flex items-center gap-2">
                    <x-admin.primary-link secondary="true" href="{{ route('bm.publisher.trash') }}">{{ __('Trash') }} <i
                            data-lucide="trash-2" class="w-4 h-4"></i>
                    </x-admin.primary-link>
                    <x-admin.primary-link href="{{ route('bm.publisher.create') }}">{{ __('Add') }} <i
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
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Address') }}</th>
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
                    ['email', true, true],
                    ['address', true, true],
                    ['status', true, true],
                    ['created_by', false, false],
                    ['created_at', false, false],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('bm.publisher.index') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3, 4, 5, 6],
                    model: 'Publisher',
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
                    const route = "{{ route('bm.publisher.show', ':id') }}";

                    const details = [{
                            label: '{{ __('Name') }}',
                            key: 'name',
                        },
                        {
                            label: '{{ __('Slug') }}',
                            key: 'slug',
                        },
                        {
                            label: '{{ __('Email') }}',
                            key: 'email',
                        },
                        {
                            label: '{{ __('Website') }}',
                            key: 'website',
                        },
                        {
                            label: '{{ __('Status') }}',
                            key: 'status_label',
                            label_color: 'status_color',
                            type: 'status',
                        },
                        {
                            label: '{{ __('Address') }}',
                            key: 'address',
                        },
                        {
                            label: '{{ __('Phone') }}',
                            key: 'phone',
                        }
                    ];

                    showDetailsModal(route, id, '{{ __('Publisher Details') }}', details);
                });
            });
        </script>
    @endpush
</x-admin::layout>
