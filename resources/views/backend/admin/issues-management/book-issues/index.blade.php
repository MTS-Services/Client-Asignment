<x-admin::layout>
    <x-slot name="title">{{ __('Book Issues List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Book Issues List') }}</x-slot>
    <x-slot name="page_slug">book_issues</x-slot>
    <section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Book Issues List') }}</h2>
                <div class="flex items-center gap-2">
                    <x-admin.primary-link secondary="true" href="{{ route('im.book-issues.trash') }}">{{ __('Trash') }}
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </x-admin.primary-link>
                    <x-admin.primary-link href="{{ route('im.book-issues.create') }}">{{ __('Add') }} <i
                            data-lucide="user-round-plus" class="w-4 h-4"></i>
                    </x-admin.primary-link>
                </div>
            </div>
        </div>
        <div class="glass-card rounded-2xl p-6">
            <table class="table datatable table-zebra">
                <thead>
                    <tr>
                        <th width="5%">{{ __('SL') }}</th>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Book') }}</th>
                        <th>{{ __('Issued By') }}</th>
                        <th>{{ __('Returned By') }}</th>
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
                    ['user_id', true, true],
                    ['book_id', true, true],
                    ['issued_by', true, true],
                    ['returned_by', true, true],
                    ['status', true, true],
                    ['creater_id', true, true],
                    ['created_at', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('im.book-issues.index') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3, 4, 5, 6, 7],
                    model: 'BookIssue',
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
                    const route = "{{ route('author.show', ':id') }}";

                    const details = [
                        {
                            label: '{{ __('User') }}',
                            key: 'username',
                        },
                        {
                            label: '{{ __('Book') }}',
                            key: 'book.title',
                        },
                        {
                            label: '{{ __('Issued By') }}',
                            key: 'issued_by_admin.name',
                        },
                        {
                            label: '{{ __('Issue Date') }}',
                            key: 'issue_date',
                        },
                        {
                            label: '{{ __('Due Date') }}',
                            key: 'due_date',
                        },
                        {
                            label: '{{ __('Return Date') }}',
                            key: 'return_date',
                        },
                        {
                            label: '{{ __('Returned By') }}',
                            key: 'returned_by_user.name',
                        },
                        {
                            label: '{{ __('Status') }}',
                            key: 'status_label',
                            type: 'status',
                        },
                        {
                            label: '{{ __('Fine Amount') }}',
                            key: 'fine_amount',
                        },
                        {
                            label: '{{ __('Fine Paid') }}',
                            key: 'fine_paid',
                        },
                        {
                            label: '{{ __('Notes') }}',
                            key: 'notes',
                        },
                    ];

                    showDetailsModal(route, id, '{{ __('Book Details') }}', details);
                });
            });
        </script>
    @endpush
</x-admin::layout>
