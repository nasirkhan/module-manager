<div>
    <x-cube::lw-table :rows="$posts" search-placeholder="{{ __('Search by name or slug…') }}">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <x-cube::lw-table-th class="w-12">#</x-cube::lw-table-th>
                <x-cube::lw-table-th column="name" :sort-col="$sortCol" :sort-dir="$sortDir">
                    @lang('post::text.name')
                </x-cube::lw-table-th>
                <x-cube::lw-table-th column="slug" :sort-col="$sortCol" :sort-dir="$sortDir">
                    @lang('post::text.slug')
                </x-cube::lw-table-th>
                <x-cube::lw-table-th column="updated_at" :sort-col="$sortCol" :sort-dir="$sortDir">
                    @lang('post::text.updated_at')
                </x-cube::lw-table-th>
                <x-cube::lw-table-th>
                    @lang('post::text.created_by')
                </x-cube::lw-table-th>
                <x-cube::lw-table-th class="text-right">
                    @lang('post::text.action')
                </x-cube::lw-table-th>
            </tr>
        </thead>
        <tbody>
            @forelse ($posts as $post)
                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-4 py-3 text-gray-900 dark:text-white">{{ $post->id }}</td>
                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                        <a
                            href="{{ route('backend.posts.show', $post) }}"
                            class="hover:text-blue-600 dark:hover:text-blue-400"
                        >{{ $post->name }}</a>
                    </td>
                    <td class="px-4 py-3">{{ $post->slug }}</td>
                    <td class="px-4 py-3">{{ $post->updated_at->diffForHumans() }}</td>
                    <td class="px-4 py-3">{{ $post->created_by_name ?? $post->created_by ?? '—' }}</td>
                    <td class="px-4 py-3">
                        @include('backend.includes.action_column', ['data' => $post, 'module_name' => 'posts'])
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-400 dark:text-gray-500">
                        @lang('No posts found.')
                    </td>
                </tr>
            @endforelse
        </tbody>
    </x-cube::lw-table>
</div>
