@extends("backend.layouts.app")

@section("title")
    {{ $$module_name_singular->name }} - {{ __($module_action) }} {{ __($module_title) }}
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
    @php $category = $$module_name_singular; @endphp

    <x-cube::backend-layout-show
        :data="$category"
        :module_name="$module_name"
        :module_path="$module_path"
        :module_title="$module_title"
        :module_icon="$module_icon"
        :module_action="$module_action"
    >
        <x-cube::backend-section-header
            :data="$category"
            :module_name="$module_name"
            :module_title="$module_title"
            :module_icon="$module_icon"
            :module_action="$module_action"
        />

        <div class="mt-4 grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Main content --}}
            <div class="space-y-5 lg:col-span-2">

                {{-- Status badge --}}
                <div class="flex flex-wrap gap-2">
                    @php
                        $statusColors = [
                            'Active'   => 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-700',
                            'Inactive' => 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-700',
                            'Draft'    => 'bg-yellow-100 text-yellow-800 border-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-400 dark:border-yellow-700',
                        ];
                        $statusColor = $statusColors[$category->status?->value] ?? 'bg-gray-100 text-gray-800 border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600';
                    @endphp
                    <span class="inline-flex items-center gap-1 rounded-md border px-2.5 py-0.5 text-xs font-semibold {{ $statusColor }}">
                        <i class="fas fa-circle text-[8px]"></i>
                        {{ $category->status?->value }}
                    </span>
                    @if ($category->group_name)
                        <span class="inline-flex items-center rounded-md border border-blue-200 bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-800 dark:border-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                            <i class="fas fa-layer-group mr-1 text-[9px]"></i>
                            {{ $category->group_name }}
                        </span>
                    @endif
                </div>

                {{-- Name --}}
                <h2 class="text-2xl font-bold leading-tight text-gray-900 dark:text-white">
                    {{ $category->name }}
                </h2>

                {{-- Description --}}
                @if ($category->description)
                    <div class="text-base leading-relaxed text-gray-600 dark:text-gray-300">
                        {{ $category->description }}
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-4">

                {{-- Image --}}
                @if ($category->image)
                    <div class="pswp-gallery overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                        <a href="{{ asset($category->image) }}" data-pswp-src="{{ asset($category->image) }}">
                            <img
                                src="{{ asset($category->image) }}"
                                alt="{{ $category->name }}"
                                class="w-full cursor-zoom-in object-cover transition-opacity hover:opacity-90"
                            />
                        </a>
                    </div>
                @endif

                {{-- Details --}}
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-700/40">
                    <h6 class="mb-3 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        @lang("Details")
                    </h6>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between gap-2">
                            <dt class="shrink-0 text-gray-500 dark:text-gray-400">@lang("Slug")</dt>
                            <dd class="break-all font-mono text-xs text-gray-700 dark:text-gray-300">{{ $category->slug }}</dd>
                        </div>
                        @if ($category->order !== null)
                            <div class="flex justify-between gap-2">
                                <dt class="shrink-0 text-gray-500 dark:text-gray-400">@lang("Order")</dt>
                                <dd class="text-gray-700 dark:text-gray-300">{{ $category->order }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                {{-- SEO / Meta --}}
                @if ($category->meta_title || $category->meta_description || $category->meta_keyword)
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-700/40">
                        <h6 class="mb-3 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            @lang("SEO / Meta")
                        </h6>
                        <dl class="space-y-3 text-sm">
                            @if ($category->meta_title)
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400">@lang("Meta Title")</dt>
                                    <dd class="mt-0.5 text-gray-700 dark:text-gray-300">{{ $category->meta_title }}</dd>
                                </div>
                            @endif
                            @if ($category->meta_keyword)
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400">@lang("Meta Keywords")</dt>
                                    <dd class="mt-0.5 text-gray-700 dark:text-gray-300">{{ $category->meta_keyword }}</dd>
                                </div>
                            @endif
                            @if ($category->meta_description)
                                <div>
                                    <dt class="text-gray-500 dark:text-gray-400">@lang("Meta Description")</dt>
                                    <dd class="mt-0.5 text-gray-700 dark:text-gray-300">{{ $category->meta_description }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                @endif
            </div>
        </div>

        {{-- Posts table --}}
        <div class="mt-8">
            <h5 class="mb-3 text-base font-semibold text-gray-900 dark:text-white">
                @lang("Posts")
                <span class="ml-1 text-sm font-normal text-gray-500 dark:text-gray-400">({{ $posts->total() }})</span>
            </h5>

            @if ($posts->count())
                <div class="overflow-x-auto">
                    <table class="w-full border border-gray-200 text-left text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-4 py-3">@lang("Name")</th>
                                <th scope="col" class="px-4 py-3">@lang("Status")</th>
                                <th scope="col" class="px-4 py-3">@lang("Type")</th>
                                <th scope="col" class="px-4 py-3">@lang("Featured")</th>
                                <th scope="col" class="px-4 py-3">@lang("Published")</th>
                                <th scope="col" class="px-4 py-3 text-right">@lang("Hits")</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $post)
                                @php
                                    $postStatusColors = [
                                        'Published'   => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                        'Unpublished' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                        'Draft'       => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                    ];
                                    $postStatusColor = $postStatusColors[$post->status?->value] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300';
                                @endphp
                                <tr class="border-b bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-600">
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                        <x-cube::link href="{{ route('backend.posts.show', [$post->id, $post->slug]) }}">
                                            {{ $post->name }}
                                        </x-cube::link>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="rounded px-2 py-0.5 text-xs font-semibold {{ $postStatusColor }}">
                                            {{ $post->status?->value }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">{{ $post->type?->value ?? '—' }}</td>
                                    <td class="px-4 py-3">
                                        @if ($post->is_featured)
                                            <span class="rounded bg-purple-100 px-2 py-0.5 text-xs font-semibold text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">@lang("Yes")</span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">{{ $post->published_at?->isoFormat('ll') ?? '—' }}</td>
                                    <td class="px-4 py-3 text-right">{{ number_format($post->hits) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $posts->links() }}
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400">@lang("No posts found.")</p>
            @endif
        </div>
    </x-cube::backend-layout-show>
@endsection
