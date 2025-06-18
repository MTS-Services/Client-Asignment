<x-admin::layout>
    <x-slot name="title">{{ __('Book Issues List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Book Issues List') }}</x-slot>
    <x-slot name="page_slug">book_issues_{{ request('status') ? request('status') : request('fine-status') }}</x-slot>
    <section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">Book {{ request('status') == 'Pending' ? 'Request' : request('status') }} List</h2>
                <div class="flex items-center gap-2">
                    <x-admin.primary-link secondary="true"
                        href="{{ route('bim.book-issues.trash', ['status' => request('status')]) }}">{{ __('Trash') }}
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </x-admin.primary-link>
                    <x-admin.primary-link
                        href="{{ route('bim.book-issues.create', ['status' => request('status')]) }}">{{ __('Add') }}
                        <i data-lucide="plus" class="w-4 h-4"></i>
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
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Issued Date') }}</th>
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
                    ['status', true, true],
                    ['issue_date', true, true],
                    ['creater_id', true, true],
                    ['created_at', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('bim.book-issues.index') }}",
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
                    const route = "{{ route('bim.book-issues.show', ':id') }}";

                    const details = [{
                            label: '{{ __('User') }}',
                            key: 'username',
                        },
                        {
                            label: '{{ __('Book') }}',
                            key: 'bookTitle',
                        },
                        {
                            label: '{{ __('Status') }}',
                            key: 'status_label',
                            label_color: 'status_color',
                            type: 'status',
                        },
                        {
                            label: '{{ __('Fine Status') }}',
                            key: 'fine_status_label',
                            label_color: 'fine_status_color',
                            type: 'status',
                        },
                        {
                            label: '{{ __('Issued By') }}',
                            key: 'issuedBy',
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
                            label: '{{ __('Returned By') }}',
                            key: 'returnedBy',
                        },
                        {
                            label: '{{ __('Return Date') }}',
                            key: 'return_date',
                        },
                        {
                            label: '{{ __('Fine Amount') }}',
                            key: 'fine_amount',
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
