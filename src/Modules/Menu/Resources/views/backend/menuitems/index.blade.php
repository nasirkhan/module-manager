@extends("backend.layouts.app")

@section("title")
    {{ __($module_action) }} {{ __($module_title) }}
@endsection

@section("breadcrumbs")
    <x-cube::backend-breadcrumbs>
        <x-cube::backend-breadcrumb-item type="active" icon="{{ $module_icon }}">
            {{ __($module_title) }}
        </x-cube::backend-breadcrumb-item>
    </x-cube::backend-breadcrumbs>
@endsection

@section("content")
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6">
            <x-cube::backend-section-header
                :module_name="$module_name"
                :module_title="$module_title"
                :module_icon="$module_icon"
                :module_action="$module_action"
            />

            <div class="overflow-x-auto mt-4">
                <table id="datatable" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Menu</th>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Parent</th>
                            <th class="px-4 py-3">URL/Route</th>
                            <th class="px-4 py-3">Order</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-3">
            <div class="flex items-center justify-end">
                <a
                    href="{{ route('backend.menus.index') }}"
                    wire:navigate
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:hover:bg-gray-700"
                    title="Manage Menus"
                >
                    <i class="fas fa-list fa-fw mr-1"></i> Manage Menus
                </a>
            </div>
        </div>
    </div>
@endsection

@push("after-styles")
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatable/datatables.min.css') }}">
@endpush

@push("after-scripts")
<script type="text/javascript" src="{{ asset('vendor/datatable/datatables.min.js') }}"></script>

<script type="text/javascript">
    $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        responsive: true,
        ajax: {
            url: '{{ route('backend.menuitems.index_data') }}',
        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'menu_name', name: 'menu.name', searchable: true},
            {
                data: 'name_with_hierarchy',
                name: 'name',
                render: function(data, type, row) {
                    let indent = '';
                    for (let i = 0; i < row.level; i++) {
                        indent += '&nbsp;&nbsp;&nbsp;&nbsp;';
                    }
                    let icon = row.icon ? '<i class="' + row.icon + '"></i> ' : '';
                    return indent + icon + data;
                },
                searchable: true
            },
            {
                data: 'type',
                name: 'type',
                render: function(data) {
                    const typeLabels = {
                        'link': '<span class="badge bg-primary">Link</span>',
                        'dropdown': '<span class="badge bg-info">Dropdown</span>',
                        'divider': '<span class="badge bg-secondary">Divider</span>',
                        'heading': '<span class="badge bg-warning">Heading</span>',
                        'external': '<span class="badge bg-success">External</span>'
                    };
                    return typeLabels[data] || data;
                }
            },
            {
                data: 'parent_name',
                name: 'parent.name',
                searchable: true,
                render: function(data) {
                    return data || '<span class="text-muted">Root Level</span>';
                }
            },
            {
                data: 'url_display',
                name: 'url',
                render: function(data, type, row) {
                    if (row.route_name) {
                        return '<code>' + row.route_name + '</code>';
                    } else if (row.url) {
                        return '<a href="' + row.url + '" target="_blank" class="text-decoration-none">' + row.url + ' <i class="fas fa-external-link-alt"></i></a>';
                    }
                    return '<span class="text-muted">N/A</span>';
                },
                searchable: false
            },
            {
                data: 'sort_order',
                name: 'sort_order',
                render: function(data) {
                    return '<span class="badge bg-light text-dark">' + (data || 0) + '</span>';
                }
            },
            {
                data: 'status_badge',
                name: 'status',
                render: function(data, type, row) {
                    let badges = '';
                    if (row.status == 1) {
                        badges += '<span class="badge bg-success">Published</span> ';
                    } else if (row.status == 0) {
                        badges += '<span class="badge bg-danger">Disabled</span> ';
                    } else {
                        badges += '<span class="badge bg-warning">Draft</span> ';
                    }
                    if (row.is_active) {
                        badges += '<span class="badge bg-info">Active</span> ';
                    }
                    if (row.is_visible) {
                        badges += '<span class="badge bg-primary">Visible</span>';
                    }
                    return badges;
                },
                searchable: false
            },
            {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end'}
        ],
        order: [[1, 'asc'], [6, 'asc']]
    });
</script>
@endpush
