<x-admin::layout>
    <x-slot name="title">{{ __('Trashed Rack List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Trashed Rack List') }}</x-slot>
    <x-slot name="page_slug">rack</x-slot>
    <section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Trashed Rack List') }}</h2>
                <x-admin.primary-link href="{{ route('bm.rack.index') }}">{{ __('Back') }} </x-admin.primary-link>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6">
            <table class="table datatable table-zebra">
                <thead>
                    <tr>
                        <th width="5%">{{ __('SL') }}</th>
                        <th>{{ __('Rack Number') }}</th>
                        <th>{{ __('Location') }}</th>
                        <th>{{ __('Capacity') }}</th>
                        <th>{{ __('Deleted By') }}</th>
                        <th>{{ __('Deleted Date') }}</th>
                        <th width="10%">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </section>

    @push('js')
        <script src="{{ asset('assets/js/datatable.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                let table_columns = [
                    //name and data, orderable, searchable
                    ['rack_number', true, true],
                    ['location', true, true],
                    ['capacity', true, true],
                    ['deleted_by', true, true],
                    ['deleted_at', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('bm.rack.trash') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3, 4, 5],
                    model: 'Rack',
                };
                initializeDataTable(details);
            })
        </script>
    @endpush
</x-admin::layout>
