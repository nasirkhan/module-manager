@extends("frontend.layouts.app")

@section("title")
    {{ $$module_name_singular->name }} - {{ __($module_title) }}
@endsection

@section("content")
    <x-cube::header-block :title="__($$module_name_singular->name)">
        <nav class="mb-4 flex items-center justify-center gap-1.5 text-sm text-gray-500 dark:text-gray-400">
            <a
                href="{{ route("frontend." . $module_name . ".index") }}"
                class="transition-colors hover:text-blue-600 dark:hover:text-blue-400"
            >
                {{ __($module_title) }}
            </a>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-gray-400 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-gray-700 dark:text-gray-300">{{ $$module_name_singular->name }}</span>
        </nav>

        @if ($$module_name_singular->description)
            <p class="mb-8 leading-relaxed">{{ $$module_name_singular->description }}</p>
        @endif
    </x-cube::header-block>

    <section class="bg-white py-10 text-gray-600 dark:bg-gray-700 sm:py-16">
        <div class="mx-auto max-w-7xl px-6 sm:px-10">
            @if ($posts->isNotEmpty())
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($posts as $post)
                        @php
                            $details_url = route("frontend.posts.show", [encode_id($post->id), $post->slug]);
                            $authorName = $post->created_by_alias ?: $post->created_by_name;
                        @endphp

                        <x-cube::card
                            :url="$details_url"
                            :name="$post->name"
                            :image="$post->image"
                        >
                            <div class="my-3 flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>{{ $authorName }}</span>
                                @if ($post->published_at)
                                    <span class="text-gray-300 dark:text-gray-600">·</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ $post->published_at->format("M j, Y") }}</span>
                                @endif
                            </div>

                            <p class="mb-3 line-clamp-3 text-sm text-gray-600 dark:text-gray-400">
                                {{ $post->intro }}
                            </p>

                            <div class="mb-2">
                                <a
                                    href="{{ route('frontend.categories.show', [encode_id($post->category_id), $post->category->slug]) }}"
                                    class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 hover:bg-blue-100 dark:bg-blue-900/40 dark:text-blue-300 dark:hover:bg-blue-900/60"
                                >
                                    {{ $post->category->name }}
                                </a>
                            </div>
                        </x-cube::card>
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="py-16 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-4 h-12 w-12 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-5 5a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 10V5a2 2 0 012-2z" />
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">{{ __("No posts found with this tag.") }}</p>
                </div>
            @endif
        </div>
    </section>
@endsection
