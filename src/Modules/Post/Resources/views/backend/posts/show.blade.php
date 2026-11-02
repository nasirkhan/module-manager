@extends("backend.layouts.app")

@section("title")
    {{ __($module_action) }} {{ __($module_title) }}
@endsection

@section("breadcrumbs")
    <x-cube::backend-breadcrumbs>
        <x-cube::backend-breadcrumb-item route='{{ route("backend.$module_name.index") }}' icon="{{ $module_icon }}">
            {{ __($module_title) }}
        </x-cube::backend-breadcrumb-item>
        <x-cube::backend-breadcrumb-item type="active">{{ __($module_action) }}</x-cube::backend-breadcrumb-item>
    </x-cube::backend-breadcrumbs>
@endsection

@section("content")
    @php $post = $$module_name_singular; @endphp

    <x-cube::backend-layout-show
        :data="$post"
        :module_name="$module_name"
        :module_path="$module_path"
        :module_title="$module_title"
        :module_icon="$module_icon"
        :module_action="$module_action"
    >
        <x-cube::backend-section-header
            :data="$post"
            :module_name="$module_name"
            :module_title="$module_title"
            :module_icon="$module_icon"
            :module_action="$module_action"
        />

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Main content --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Status / Type / Featured badges --}}
                <div class="flex flex-wrap gap-2">
                    @php
                        $statusColors = [
                            'Published'   => 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-700',
                            'Unpublished' => 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-700',
                            'Draft'       => 'bg-yellow-100 text-yellow-800 border-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-400 dark:border-yellow-700',
                        ];
                        $statusColor = $statusColors[$post->status?->value] ?? 'bg-gray-100 text-gray-800 border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600';
                    @endphp
                    <span class="inline-flex items-center gap-1 rounded-md border px-2.5 py-0.5 text-xs font-semibold {{ $statusColor }}">
                        <i class="fas fa-circle text-[8px]"></i>
                        {{ $post->status?->value }}
                    </span>
                    @if ($post->type)
                        <span class="inline-flex items-center rounded-md border border-blue-200 bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-800 dark:border-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                            {{ $post->type?->value }}
                        </span>
                    @endif
                    @if ($post->is_featured)
                        <span class="inline-flex items-center gap-1 rounded-md border border-purple-200 bg-purple-100 px-2.5 py-0.5 text-xs font-semibold text-purple-800 dark:border-purple-700 dark:bg-purple-900/30 dark:text-purple-400">
                            <i class="fas fa-star text-[9px]"></i>
                            @lang("Featured")
                        </span>
                    @endif
                </div>

                {{-- Title --}}
                <h2 class="text-2xl font-bold leading-tight text-gray-900 dark:text-white">
                    {{ $post->name }}
                </h2>

                {{-- Intro --}}
                @if ($post->intro)
                    <blockquote class="border-l-4 border-indigo-400 pl-4 text-base italic leading-relaxed text-gray-600 dark:text-gray-300">
                        {{ $post->intro }}
                    </blockquote>
                @endif

                {{-- Content --}}
                @if ($post->content)
                    <div class="prose prose-sm max-w-none text-gray-700 dark:prose-invert dark:text-gray-300">
                        {!! $post->content !!}
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-4">

                {{-- Featured image --}}
                @if ($post->image)
                    <div class="pswp-gallery overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                        <a href="{{ asset($post->image) }}" data-pswp-src="{{ asset($post->image) }}">
                            <img
                                src="{{ asset($post->image) }}"
                                alt="{{ $post->name }}"
                                class="w-full object-cover cursor-zoom-in transition-opacity hover:opacity-90"
                            />
                        </a>
                    </div>
                @endif

                {{-- Category --}}
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-700/40">
                    <h6 class="mb-3 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        @lang("Category")
                    </h6>
                    @if ($post->category)
                        <x-cube::link href="{{ route('backend.categories.show', [$post->category_id, $post->category->slug]) }}">
                            <i class="fas fa-folder mr-1 text-indigo-500"></i>
                            {{ $post->category->name }}
                        </x-cube::link>
                    @else
                        <span class="text-sm text-gray-400 dark:text-gray-500">@lang("None")</span>
                    @endif
                </div>

                {{-- Tags --}}
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-700/40">
                    <h6 class="mb-3 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        @lang("Tags")
                        @if ($post->tags->count())
                            <span class="ml-1 rounded-full bg-gray-200 px-1.5 py-0.5 text-xs dark:bg-gray-600">{{ $post->tags->count() }}</span>
                        @endif
                    </h6>
                    @if ($post->tags->count())
                        <div class="flex flex-wrap">
                            @foreach ($post->tags as $tag)
                                <x-cube::badge
                                    url="{{ route('backend.tags.show', [$tag->id, $tag->slug]) }}"
                                    text="{{ $tag->name }}"
                                />
                            @endforeach
                        </div>
                    @else
                        <span class="text-sm text-gray-400 dark:text-gray-500">@lang("No tags")</span>
                    @endif
                </div>

                {{-- Post details --}}
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-700/40">
                    <h6 class="mb-3 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        @lang("Details")
                    </h6>
                    <dl class="space-y-2 text-sm">
                        @if ($post->created_by_name)
                            <div class="flex justify-between gap-2">
                                <dt class="shrink-0 text-gray-500 dark:text-gray-400">@lang("Author")</dt>
                                <dd class="text-right text-gray-700 dark:text-gray-300">{{ $post->created_by_name }}</dd>
                            </div>
                        @endif
                        @if ($post->created_by_alias)
                            <div class="flex justify-between gap-2">
                                <dt class="shrink-0 text-gray-500 dark:text-gray-400">@lang("Author Alias")</dt>
                                <dd class="text-right text-gray-700 dark:text-gray-300">{{ $post->created_by_alias }}</dd>
                            </div>
                        @endif
                        <div class="flex justify-between gap-2">
                            <dt class="shrink-0 text-gray-500 dark:text-gray-400">@lang("Published")</dt>
                            <dd class="text-right text-gray-700 dark:text-gray-300">{{ $post->published_at?->isoFormat('lll') ?? '—' }}</dd>
                        </div>
                        <div class="flex justify-between gap-2">
                            <dt class="shrink-0 text-gray-500 dark:text-gray-400">@lang("Slug")</dt>
                            <dd class="break-all font-mono text-xs text-gray-700 dark:text-gray-300">{{ $post->slug }}</dd>
                        </div>
                        <div class="flex justify-between gap-2">
                            <dt class="shrink-0 text-gray-500 dark:text-gray-400">@lang("Hits")</dt>
                            <dd class="text-gray-700 dark:text-gray-300">{{ number_format($post->hits) }}</dd>
                        </div>
                        @if ($post->order !== null)
                            <div class="flex justify-between gap-2">
                                <dt class="shrink-0 text-gray-500 dark:text-gray-400">@lang("Order")</dt>
                                <dd class="text-gray-700 dark:text-gray-300">{{ $post->order }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                {{-- SEO / Meta (only if any meta fields are set) --}}
                @if ($post->meta_title || $post->meta_keywords || $post->meta_description || $post->meta_og_image || $post->meta_og_url)
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-700/40">
                        <h6 class="mb-3 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            @lang("SEO / Meta")
                        </h6>
                        <dl class="space-y-3 text-sm">
                            @if ($post->meta_title)
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400">@lang("Meta Title")</dt>
                                    <dd class="mt-0.5 text-gray-700 dark:text-gray-300">{{ $post->meta_title }}</dd>
                                </div>
                            @endif
                            @if ($post->meta_keywords)
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400">@lang("Meta Keywords")</dt>
                                    <dd class="mt-0.5 text-gray-700 dark:text-gray-300">{{ $post->meta_keywords }}</dd>
                                </div>
                            @endif
                            @if ($post->meta_description)
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400">@lang("Meta Description")</dt>
                                    <dd class="mt-0.5 text-gray-700 dark:text-gray-300">{{ $post->meta_description }}</dd>
                                </div>
                            @endif
                            @if ($post->meta_og_image)
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400">@lang("OG Image")</dt>
                                    <dd class="mt-0.5 break-all text-gray-700 dark:text-gray-300">{{ $post->meta_og_image }}</dd>
                                </div>
                            @endif
                            @if ($post->meta_og_url)
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400">@lang("OG URL")</dt>
                                    <dd class="mt-0.5 break-all text-gray-700 dark:text-gray-300">{{ $post->meta_og_url }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                @endif

            </div>
        </div>
    </x-cube::backend-layout-show>
@endsection
