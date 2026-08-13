@extends("frontend.layouts.app")

@section("title")
    {{ $$module_name_singular->name }}
@endsection

@section("content")
    @php
        $shareUrl = route("frontend.posts.show", [encode_id($$module_name_singular->id), $$module_name_singular->slug]);
        $shareDescription = $$module_name_singular->meta_description ?: $$module_name_singular->intro;
        $shareImage = $$module_name_singular->meta_og_image ?: $$module_name_singular->image;

        if ($shareImage && ! \Illuminate\Support\Str::startsWith($shareImage, ["http://", "https://"])) {
            $shareImage = asset($shareImage);
        }

        $authorName = $$module_name_singular->created_by_alias
            ?: $$module_name_singular->created_by_name;
    @endphp

    {{-- Hero: two-column on desktop --}}
    <section class="bg-gray-100 px-6 text-gray-600 sm:px-10 dark:bg-gray-800 dark:text-gray-400">
        <div class="mx-auto max-w-6xl py-10 sm:py-14">

            <div class="flex flex-col gap-8 lg:flex-row lg:items-center lg:gap-14">

                {{-- Left: title, intro, meta --}}
                <div class="flex flex-col lg:w-1/3">
                    <h1 class="mb-4 text-3xl font-semibold leading-tight text-gray-800 sm:text-4xl dark:text-gray-100">
                        {{ $$module_name_singular->name }}
                    </h1>

                    @if ($$module_name_singular->intro != "")
                        <p class="mb-6 text-lg leading-relaxed text-gray-500 dark:text-gray-400">
                            {{ $$module_name_singular->intro }}
                        </p>
                    @endif

                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                        <span class="flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ $authorName }}
                        </span>
                        @if ($$module_name_singular->published_at)
                            <span class="flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $$module_name_singular->published_at->isoFormat("ll") }}
                            </span>
                        @endif
                    </div>

                    @include("frontend.includes.messages")
                </div>

                {{-- Right: featured image --}}
                @if ($$module_name_singular->image)
                    <div class="lg:w-2/3">
                        <img
                            class="max-h-[420px] w-full rounded-xl object-cover shadow-md"
                            src="{{ $$module_name_singular->image }}"
                            alt="{{ $$module_name_singular->name }}"
                        />
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Body --}}
    <section class="px-6 py-10 sm:px-10 sm:py-14 dark:bg-gray-700 dark:text-gray-300">
        <div class="mx-auto max-w-6xl">
            <div class="flex flex-col gap-10 lg:flex-row">

                {{-- Main content --}}
                <div class="min-w-0 lg:flex-1">
                    <div class="prose prose-gray max-w-none dark:prose-invert prose-headings:font-semibold prose-a:text-blue-600 prose-img:rounded-lg prose-img:shadow-sm">
                        {!! $$module_name_singular->content !!}
                    </div>

                    <hr class="my-8 dark:border-gray-600" />

                    {{-- Category --}}
                    <div class="mb-4 flex items-center gap-2">
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">{{ __("Category") }}:</span>
                        <a
                            href="{{ route('frontend.categories.show', [encode_id($$module_name_singular->category_id), $$module_name_singular->category->slug]) }}"
                            class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 hover:bg-blue-100 dark:bg-blue-900/40 dark:text-blue-300 dark:hover:bg-blue-900/60"
                        >
                            {{ $$module_name_singular->category->name }}
                        </a>
                    </div>

                    {{-- Tags --}}
                    @if (count($$module_name_singular->tags))
                        <div class="mb-6 flex flex-wrap items-center gap-1">
                            <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">{{ __("Tags") }}:</span>
                            @foreach ($$module_name_singular->tags as $tag)
                                <x-cube::badge
                                    :url="route('frontend.tags.show', [encode_id($tag->id), $tag->slug])"
                                    :text="$tag->name"
                                />
                            @endforeach
                        </div>
                    @endif

                    {{-- Share --}}
                    <div class="mt-6">
                        <x-sharekit::buttons
                            theme="tailwind"
                            label="{{ __('Share with others') }}"
                            :url="$shareUrl"
                            :title="$$module_name_singular->name"
                            :description="$shareDescription"
                            :image="$shareImage"
                            :networks="['x', 'facebook', 'linkedin', 'whatsapp', 'telegram', 'email', 'copy', 'native']"
                            size="sm"
                            :show-heading="true"
                        />
                    </div>
                </div>

                {{-- Sidebar --}}
                <aside class="w-full shrink-0 lg:w-72">
                    <div class="sticky top-6">
                        <livewire:post.frontend-recent-posts />
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
