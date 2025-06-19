<x-user::layout>
    <x-slot name="title">{{ __('Book Issues List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Book Issues List') }}</x-slot>
    <x-slot name="page_slug">book_issues_{{ request('status') ? request('status') : request('fine-status') }}</x-slot>
    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">Book {{ request('status') == 'Pending' ? 'Request' : request('status') }} List
                </h2>
                <div class="flex items-center gap-2">
                    <x-admin.primary-link href="{{ route('user.book-issues-create') }}">{{ __('Book Request') }}<i
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
                        <th>{{ __('Book') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Created Date') }}</th>
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
                    ['book_id', true, true],
                    ['status', true, true],
                    ['created_at', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('user.book-issues-list') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3],
                    model: 'BookIssue',
                };
                // initializeDataTable(details);

                initializeDataTable(details);
            })
        </script>
    @endpush
</x-user::layout>
