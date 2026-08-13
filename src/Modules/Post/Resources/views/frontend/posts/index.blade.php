@extends("frontend.layouts.app")

@section("title")
    {{ __($module_title) }}
@endsection

@section("content")
    <x-cube::header-block :title="__('Articles')">
        <p class="mb-8 leading-relaxed">
            {{ __("We publish articles on a number of topics.") }}
            <br />
            {{ __("We encourage you to read our posts and let us know your feedback.") }}
        </p>
    </x-cube::header-block>

    <section class="bg-white py-10 text-gray-600 dark:bg-gray-700 sm:py-16">
        <div class="mx-auto max-w-7xl px-6 sm:px-10">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($$module_name as $$module_name_singular)
                    @php
                        $details_url = route("frontend.$module_name.show", [
                            encode_id($$module_name_singular->id),
                            $$module_name_singular->slug,
                        ]);
                        $authorName = $$module_name_singular->created_by_alias
                            ?: $$module_name_singular->created_by_name;
                        $authorUrl = $$module_name_singular->created_by_alias
                            ? null
                            : route("frontend.users.profile", $$module_name_singular->created_by);
                    @endphp

                    <x-cube::card
                        :url="$details_url"
                        :name="$$module_name_singular->name"
                        :image="$$module_name_singular->image"
                    >
                        {{-- Author + date --}}
                        <div class="my-3 flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            @if ($authorUrl)
                                <a href="{{ $authorUrl }}" class="hover:text-gray-700 dark:hover:text-gray-200">{{ $authorName }}</a>
                            @else
                                <span>{{ $authorName }}</span>
                            @endif
                            @if ($$module_name_singular->published_at)
                                <span class="text-gray-300 dark:text-gray-600">·</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>{{ $$module_name_singular->published_at->format("M j, Y") }}</span>
                            @endif
                        </div>

                        {{-- Intro --}}
                        <p class="mb-3 line-clamp-3 text-sm text-gray-600 dark:text-gray-400">
                            {{ $$module_name_singular->intro }}
                        </p>

                        {{-- Category --}}
                        <div class="mb-2">
                            <a
                                href="{{ route('frontend.categories.show', [encode_id($$module_name_singular->category_id), $$module_name_singular->category->slug]) }}"
                                class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 hover:bg-blue-100 dark:bg-blue-900/40 dark:text-blue-300 dark:hover:bg-blue-900/60"
                            >
                                {{ $$module_name_singular->category->name }}
                            </a>
                        </div>

                        {{-- Tags --}}
                        @if ($$module_name_singular->tags->isNotEmpty())
                            <div class="flex flex-wrap">
                                @foreach ($$module_name_singular->tags as $tag)
                                    <x-cube::badge
                                        :url="route('frontend.tags.show', [encode_id($tag->id), $tag->slug])"
                                        :text="$tag->name"
                                    />
                                @endforeach
                            </div>
                        @endif
                    </x-cube::card>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $$module_name->links() }}
            </div>
        </div>
    </section>
@endsection
