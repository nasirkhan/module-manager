<div>
    <x-cube::lw-table :rows="$tags" search-placeholder="{{ __('Search by name…') }}">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <x-cube::lw-table-th class="w-12">#</x-cube::lw-table-th>
                <x-cube::lw-table-th column="name" :sort-col="$sortCol" :sort-dir="$sortDir">
                    @lang('tag::text.name')
                </x-cube::lw-table-th>
                <x-cube::lw-table-th column="updated_at" :sort-col="$sortCol" :sort-dir="$sortDir">
                    @lang('tag::text.updated_at')
                </x-cube::lw-table-th>
                <x-cube::lw-table-th class="text-right">
                    @lang('tag::text.action')
                </x-cube::lw-table-th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tags as $tag)
                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-4 py-3 text-gray-900 dark:text-white">{{ $tag->id }}</td>
                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                        <a
                            href="{{ route('backend.tags.show', $tag) }}"
                            class="hover:text-blue-600 dark:hover:text-blue-400"
                        >{{ $tag->name }}</a>
                    </td>
                    <td class="px-4 py-3">{{ $tag->updated_at->diffForHumans() }}</td>
                    <td class="px-4 py-3">
                        @include('backend.includes.action_column', ['data' => $tag, 'module_name' => 'tags'])
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-400 dark:text-gray-500">
                        @lang('No tags found.')
                    </td>
                </tr>
            @endforelse
        </tbody>
    </x-cube::lw-table>
</div>
