<div>
    <x-cube::lw-table :rows="$menus" search-placeholder="{{ __('Search by name or slug…') }}">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <x-cube::lw-table-th column="id" :sort-col="$sortCol" :sort-dir="$sortDir" class="w-12">
                    #
                </x-cube::lw-table-th>
                <x-cube::lw-table-th column="name" :sort-col="$sortCol" :sort-dir="$sortDir">
                    @lang('menu::text.name')
                </x-cube::lw-table-th>
                <x-cube::lw-table-th column="slug" :sort-col="$sortCol" :sort-dir="$sortDir">
                    @lang('menu::text.slug')
                </x-cube::lw-table-th>
                <x-cube::lw-table-th column="updated_at" :sort-col="$sortCol" :sort-dir="$sortDir">
                    @lang('menu::text.updated_at')
                </x-cube::lw-table-th>
                <x-cube::lw-table-th class="text-right">
                    @lang('menu::text.action')
                </x-cube::lw-table-th>
            </tr>
        </thead>
        <tbody>
            @forelse ($menus as $menu)
                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-4 py-3 text-gray-900 dark:text-white">{{ $menu->id }}</td>
                    <td class="px-4 py-3">
                        <a
                            href="{{ route('backend.menus.show', $menu) }}"
                            class="font-semibold text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400"
                        >{{ $menu->name }}</a>
                    </td>
                    <td class="px-4 py-3">{{ $menu->slug }}</td>
                    <td class="px-4 py-3">{{ $menu->updated_at->diffForHumans() }}</td>
                    <td class="px-4 py-3">
                        @include('admin::includes.action_column', ['data' => $menu, 'module_name' => 'menus'])
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-400 dark:text-gray-500">
                        @lang('No menus found.')
                    </td>
                </tr>
            @endforelse
        </tbody>
    </x-cube::lw-table>
</div>
