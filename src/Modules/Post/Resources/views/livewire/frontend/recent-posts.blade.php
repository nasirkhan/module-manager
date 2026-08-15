<div class="w-full rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

    <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-700">
        <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
            @lang("Recent Posts")
        </h3>
    </div>

    <ul class="divide-y divide-gray-100 dark:divide-gray-700">
        @foreach ($recentPosts as $row)
            @php
                $details_url = route("frontend.posts.show", [encode_id($row->id), $row->slug]);
                $authorName = $row->created_by_alias ?: $row->created_by_name;
            @endphp

            <li>
                <a
                    href="{{ $details_url }}"
                    wire:navigate
                    class="flex items-start gap-3 px-4 py-3 transition-colors hover:bg-gray-50 dark:hover:bg-gray-700/50"
                >
                    <img
                        class="mt-0.5 h-14 w-14 shrink-0 rounded object-cover"
                        src="{{ $row->image }}"
                        alt="{{ $row->name }}"
                    />
                    <div class="min-w-0 flex-1">
                        <p class="line-clamp-2 text-sm font-medium leading-snug text-gray-800 dark:text-gray-200">
                            {{ $row->name }}
                        </p>
                        <p class="mt-1 truncate text-xs text-gray-400 dark:text-gray-500">
                            {{ $authorName }}
                        </p>
                        @if ($row->published_at)
                            <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">
                                {{ $row->published_at->format("M j, Y") }}
                            </p>
                        @endif
                    </div>
                </a>
            </li>
        @endforeach
    </ul>

</div>
