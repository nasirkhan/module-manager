@php
    $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level);
@endphp

<tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
    <td class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">{{ $item->sort_order }}</td>
    <td class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
        {!! $indent !!}
        @if($item->icon)
            <i class="{{ $item->icon }}"></i>
        @endif
        {{ $item->name }}
        @if($item->children->count() > 0)
            <span class="inline-flex items-center px-1.5 py-0.5 text-xs font-medium text-white bg-sky-500 rounded ml-1">
                {{ $item->children->count() }} child{{ $item->children->count() > 1 ? 'ren' : '' }}
            </span>
        @endif
    </td>
    <td class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
        <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-gray-700 bg-gray-100 dark:bg-gray-700 dark:text-gray-300 rounded">
            {{ ucfirst($item->type) }}
        </span>
    </td>
    <td class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 text-xs">
        @if($item->url)
            <span class="text-gray-400">URL:</span> {{ Str::limit($item->url, 30) }}
        @elseif($item->route_name)
            <span class="text-gray-400">Route:</span> {{ $item->route_name }}
        @else
            <span class="text-gray-400">-</span>
        @endif
        @if($item->opens_new_tab)
            <i class="fas fa-external-link-alt text-gray-400 ml-1" title="Opens in new tab"></i>
        @endif
    </td>
    <td class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
        <div class="flex flex-wrap gap-1">
            @if($item->is_active)
                <span class="inline-flex items-center px-1.5 py-0.5 text-xs font-medium text-white bg-green-600 rounded">{{ __('menu::text.active') }}</span>
            @else
                <span class="inline-flex items-center px-1.5 py-0.5 text-xs font-medium text-white bg-yellow-500 rounded">{{ __('menu::text.inactive') }}</span>
            @endif
            @if($item->is_visible)
                <span class="inline-flex items-center px-1.5 py-0.5 text-xs font-medium text-white bg-blue-600 rounded">{{ __('menu::text.visible') }}</span>
            @else
                <span class="inline-flex items-center px-1.5 py-0.5 text-xs font-medium text-white bg-gray-500 rounded">{{ __('menu::text.hidden') }}</span>
            @endif
        </div>
    </td>
    <td class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 text-center">
        <div class="flex items-center justify-center gap-1">
            <a
                href="{{ route('backend.menuitems.show', $item->id) }}"
                class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-700 border border-blue-700 rounded hover:bg-blue-50 dark:border-blue-400 dark:text-blue-400 dark:hover:bg-blue-900/20"
                title="View"
            >
                <i class="fas fa-eye"></i>
            </a>
            <a
                href="{{ route('backend.menuitems.edit', $item->id) }}"
                class="inline-flex items-center px-2 py-1 text-xs font-medium text-yellow-700 border border-yellow-700 rounded hover:bg-yellow-50 dark:border-yellow-400 dark:text-yellow-400 dark:hover:bg-yellow-900/20"
                title="Edit"
            >
                <i class="fas fa-edit"></i>
            </a>
            @if($item->children->count() == 0)
                <form method="POST" action="{{ route('backend.menuitems.destroy', $item->id) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-700 border border-red-700 rounded hover:bg-red-50 dark:border-red-400 dark:text-red-400 dark:hover:bg-red-900/20"
                        title="Delete"
                        onclick="return confirm('Are you sure you want to delete this menu item?')"
                    >
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            @endif
        </div>
    </td>
</tr>

@if($item->children->count() > 0)
    @foreach($item->children->sortBy('sort_order') as $child)
        @include('menu::backend.menus.partials.menu-item-row', ['item' => $child, 'level' => $level + 1])
    @endforeach
@endif
