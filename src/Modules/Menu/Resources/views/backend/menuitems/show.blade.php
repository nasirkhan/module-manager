@extends('backend.layouts.app')

@section('title') {{ __($module_action) }} {{ __($module_title) }} @endsection

@section('breadcrumbs')
<x-cube::backend-breadcrumbs>
    <x-cube::backend-breadcrumb-item route='{{ route("backend.menus.index") }}' icon='fa-solid fa-list'>
        {{ __('Menus') }}
    </x-cube::backend-breadcrumb-item>
    <x-cube::backend-breadcrumb-item route='{{ route("backend.menus.show", $$module_name_singular->menu_id) }}' icon='fa-solid fa-list'>
        {{ $$module_name_singular->menu->name }}
    </x-cube::backend-breadcrumb-item>
    <x-cube::backend-breadcrumb-item type="active">{{ $$module_name_singular->name }}</x-cube::backend-breadcrumb-item>
</x-cube::backend-breadcrumbs>
@endsection

@section('content')

@php
    $type_labels = [
        'link'     => 'Link',
        'dropdown' => 'Dropdown',
        'divider'  => 'Divider',
        'heading'  => 'Heading',
        'external' => 'External Link',
    ];
    $type_colors = [
        'link'     => 'bg-blue-600',
        'dropdown' => 'bg-sky-500',
        'divider'  => 'bg-gray-500',
        'heading'  => 'bg-yellow-500',
        'external' => 'bg-green-600',
    ];
@endphp

<x-cube::backend-layout-show :data="$$module_name_singular">

    <x-cube::backend-section-header>
        <i class="{{ $module_icon }} fa-fw"></i>
        {{ $$module_name_singular->name }}
        <small class="text-gray-500 dark:text-gray-400">{{ __($module_title) }}</small>

        <x-slot name="toolbar">
            <x-cube::backend-button-return-back :small="true" />
            <a
                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:hover:bg-gray-700 m-0.5"
                href="{{ route('backend.menus.show', $$module_name_singular->menu_id) }}"
                wire:navigate
                title="Back to Menu"
            >
                <i class="fas fa-arrow-left"></i>&nbsp;{{ __('menu::text.back_to_menu') }}
            </a>
            <x-cube::backend-button-edit
                title="{{ __('Edit') }} {{ __($module_title) }}"
                route='{!! route("backend.menuitems.edit", $$module_name_singular) !!}'
                :small="true"
            />
        </x-slot>
    </x-cube::backend-section-header>

    {{-- Basic info grid --}}
    <dl class="grid grid-cols-2 sm:grid-cols-4 gap-x-6 gap-y-5 text-sm py-2">
        <div>
            <dt class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">{{ __('menu::text.menu') }}</dt>
            <dd class="mt-0.5">
                <a href="{{ route('backend.menus.show', $$module_name_singular->menu) }}" class="text-blue-600 hover:underline dark:text-blue-400" wire:navigate>
                    {{ $$module_name_singular->menu->name }}
                </a>
            </dd>
        </div>
        <div>
            <dt class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">{{ __('menu::text.type') }}</dt>
            <dd class="mt-1">
                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white rounded {{ $type_colors[$$module_name_singular->type] ?? 'bg-gray-500' }}">
                    {{ $type_labels[$$module_name_singular->type] ?? $$module_name_singular->type }}
                </span>
            </dd>
        </div>
        @if ($$module_name_singular->parent)
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">{{ __('menu::text.parent') }}</dt>
                <dd class="mt-0.5">
                    <a href="{{ route('backend.menuitems.show', $$module_name_singular->parent) }}" class="text-blue-600 hover:underline dark:text-blue-400" wire:navigate>
                        {{ $$module_name_singular->parent->name }}
                    </a>
                </dd>
            </div>
        @endif
        <div>
            <dt class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">{{ __('menu::text.sort_order') }}</dt>
            <dd class="mt-1">
                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-gray-700 bg-gray-100 rounded dark:bg-gray-700 dark:text-gray-300">
                    {{ $$module_name_singular->sort_order ?? 0 }}
                </span>
            </dd>
        </div>
        @if ($$module_name_singular->slug)
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">{{ __('menu::text.slug') }}</dt>
                <dd class="mt-0.5 font-mono text-xs text-gray-700 dark:text-gray-300">{{ $$module_name_singular->slug }}</dd>
            </div>
        @endif
        <div>
            <dt class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">{{ __('menu::text.status') }}</dt>
            <dd class="mt-1 flex flex-wrap gap-1">
                @if ($$module_name_singular->status == 1)
                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-green-600 rounded">{{ __('menu::text.published') }}</span>
                @elseif ($$module_name_singular->status == 0)
                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-red-500 rounded">{{ __('menu::text.disabled') }}</span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-yellow-500 rounded">{{ __('menu::text.draft') }}</span>
                @endif
                @if ($$module_name_singular->is_active)
                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-green-600 rounded">{{ __('menu::text.active') }}</span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-gray-500 rounded">{{ __('menu::text.inactive') }}</span>
                @endif
                @if ($$module_name_singular->is_visible)
                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-blue-600 rounded">{{ __('menu::text.visible') }}</span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-gray-500 rounded">{{ __('menu::text.hidden') }}</span>
                @endif
            </dd>
        </div>
        @if ($$module_name_singular->locale)
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">{{ __('menu::text.locale') }}</dt>
                <dd class="mt-1">
                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-indigo-600 rounded">{{ strtoupper($$module_name_singular->locale) }}</span>
                </dd>
            </div>
        @endif
    </dl>

    {{-- Navigation & Display --}}
    @if ($$module_name_singular->url || $$module_name_singular->route_name || $$module_name_singular->icon || $$module_name_singular->badge_text || $$module_name_singular->opens_new_tab || $$module_name_singular->css_classes)
        <hr class="my-5 border-gray-200 dark:border-gray-700">

        <h6 class="mb-3 text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">
            {{ __('menu::text.navigation_display') }}
        </h6>

        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
            @if ($$module_name_singular->url)
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">{{ __('menu::text.url') }}</dt>
                    <dd class="mt-0.5">
                        <a href="{{ $$module_name_singular->url }}" target="_blank" class="inline-flex items-center gap-1 text-blue-600 hover:underline dark:text-blue-400 break-all">
                            {{ $$module_name_singular->url }}&nbsp;<i class="fas fa-external-link-alt text-xs"></i>
                        </a>
                    </dd>
                </div>
            @endif
            @if ($$module_name_singular->route_name)
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">{{ __('menu::text.route') }}</dt>
                    <dd class="mt-0.5 font-mono text-xs text-gray-700 dark:text-gray-300">
                        {{ $$module_name_singular->route_name }}
                        @if ($$module_name_singular->route_parameters)
                            <p class="mt-0.5 font-sans text-gray-400">{{ $$module_name_singular->route_parameters }}</p>
                        @endif
                    </dd>
                </div>
            @endif
            @if ($$module_name_singular->icon)
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">{{ __('menu::text.icon') }}</dt>
                    <dd class="mt-0.5 flex items-center gap-2 text-gray-700 dark:text-gray-300">
                        <i class="{{ $$module_name_singular->icon }}"></i>
                        <code class="text-xs">{{ $$module_name_singular->icon }}</code>
                    </dd>
                </div>
            @endif
            @if ($$module_name_singular->badge_text)
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">{{ __('menu::text.badge') }}</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-{{ $$module_name_singular->badge_color ?? 'blue' }}-600 rounded">
                            {{ $$module_name_singular->badge_text }}
                        </span>
                    </dd>
                </div>
            @endif
            @if ($$module_name_singular->opens_new_tab)
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">{{ __('menu::text.opens_new_tab') }}</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-sky-500 rounded">{{ __('Yes') }}</span>
                    </dd>
                </div>
            @endif
            @if ($$module_name_singular->css_classes)
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">{{ __('menu::text.css_classes') }}</dt>
                    <dd class="mt-0.5 font-mono text-xs text-gray-700 dark:text-gray-300">{{ $$module_name_singular->css_classes }}</dd>
                </div>
            @endif
        </dl>
    @endif

    {{-- Access control --}}
    @php
        $hasPermissions = isset($$module_name_singular->permissions) && is_array($$module_name_singular->permissions) && count($$module_name_singular->permissions) > 0;
        $hasRoles       = isset($$module_name_singular->roles) && is_array($$module_name_singular->roles) && count($$module_name_singular->roles) > 0;
    @endphp
    @if ($hasPermissions || $hasRoles)
        <hr class="my-5 border-gray-200 dark:border-gray-700">

        <h6 class="mb-3 text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">
            {{ __('menu::text.access_control') }}
        </h6>

        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
            @if ($hasPermissions)
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">{{ __('menu::text.permissions') }}</dt>
                    <dd class="mt-1 flex flex-wrap gap-1">
                        @foreach ($$module_name_singular->permissions as $permission)
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-yellow-500 rounded">{{ $permission }}</span>
                        @endforeach
                    </dd>
                </div>
            @endif
            @if ($hasRoles)
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">{{ __('menu::text.roles') }}</dt>
                    <dd class="mt-1 flex flex-wrap gap-1">
                        @foreach ($$module_name_singular->roles as $role)
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-indigo-600 rounded">{{ $role }}</span>
                        @endforeach
                    </dd>
                </div>
            @endif
        </dl>
    @endif

    {{-- Description / SEO --}}
    @if ($$module_name_singular->description || $$module_name_singular->meta_title)
        <hr class="my-5 border-gray-200 dark:border-gray-700">

        <h6 class="mb-3 text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">
            {{ __('menu::text.description_seo') }}
        </h6>

        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
            @if ($$module_name_singular->description)
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">{{ __('menu::text.description') }}</dt>
                    <dd class="mt-0.5 text-gray-700 dark:text-gray-300">{{ $$module_name_singular->description }}</dd>
                </div>
            @endif
            @if ($$module_name_singular->meta_title)
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">{{ __('menu::text.meta_title') }}</dt>
                    <dd class="mt-0.5 text-gray-700 dark:text-gray-300">{{ $$module_name_singular->meta_title }}</dd>
                </div>
            @endif
        </dl>
    @endif

    {{-- Additional data --}}
    @if ($$module_name_singular->custom_data || $$module_name_singular->html_attributes || $$module_name_singular->note)
        <hr class="my-5 border-gray-200 dark:border-gray-700">

        <h6 class="mb-3 text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">
            {{ __('menu::text.additional_data') }}
        </h6>

        <dl class="grid grid-cols-1 gap-y-4 text-sm">
            @if ($$module_name_singular->custom_data)
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">{{ __('menu::text.custom_data') }}</dt>
                    <dd class="mt-0.5">
                        <pre class="rounded-lg bg-gray-100 p-3 text-xs text-gray-700 overflow-x-auto dark:bg-gray-900 dark:text-gray-300"><code>{{ $$module_name_singular->custom_data }}</code></pre>
                    </dd>
                </div>
            @endif
            @if ($$module_name_singular->html_attributes)
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">{{ __('menu::text.html_attributes') }}</dt>
                    <dd class="mt-0.5">
                        <pre class="rounded-lg bg-gray-100 p-3 text-xs text-gray-700 overflow-x-auto dark:bg-gray-900 dark:text-gray-300"><code>{{ is_array($$module_name_singular->html_attributes) ? json_encode($$module_name_singular->html_attributes, JSON_PRETTY_PRINT) : $$module_name_singular->html_attributes }}</code></pre>
                    </dd>
                </div>
            @endif
            @if ($$module_name_singular->note)
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">{{ __('menu::text.admin_notes') }}</dt>
                    <dd class="mt-0.5 text-gray-700 dark:text-gray-300">{{ $$module_name_singular->note }}</dd>
                </div>
            @endif
        </dl>
    @endif

    {{-- Child items --}}
    @if ($$module_name_singular->children && $$module_name_singular->children->count() > 0)
        <hr class="my-5 border-gray-200 dark:border-gray-700">

        <div class="flex items-center justify-between mb-3">
            <h6 class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500 flex items-center gap-2">
                <i class="fas fa-sitemap"></i>
                {{ __('menu::text.child_items') }}
                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-blue-600 rounded">
                    {{ $$module_name_singular->children->count() }}
                </span>
            </h6>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 border-collapse">
                <thead>
                    <tr class="text-xs uppercase bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                        <th class="px-4 py-3">{{ __('menu::text.name') }}</th>
                        <th class="px-4 py-3">{{ __('menu::text.type') }}</th>
                        <th class="px-4 py-3">{{ __('menu::text.sort_order') }}</th>
                        <th class="px-4 py-3">{{ __('menu::text.status') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('menu::text.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach ($$module_name_singular->children->sortBy('sort_order') as $child)
                        <tr class="bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                @if ($child->icon)
                                    <i class="{{ $child->icon }} mr-1 text-gray-400"></i>
                                @endif
                                {{ $child->name }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white rounded {{ $type_colors[$child->type] ?? 'bg-gray-500' }}">
                                    {{ $type_labels[$child->type] ?? $child->type }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-gray-700 bg-gray-100 rounded dark:bg-gray-700 dark:text-gray-300">
                                    {{ $child->sort_order ?? 0 }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if ($child->status == 1 && $child->is_active && $child->is_visible)
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-green-600 rounded">{{ __('menu::text.active') }}</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-gray-500 rounded">{{ __('menu::text.inactive') }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="inline-flex gap-1">
                                    <a href="{{ route('backend.menuitems.show', $child) }}" wire:navigate
                                       class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-sky-500 rounded hover:bg-sky-600">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('backend.menuitems.edit', $child) }}" wire:navigate
                                       class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-yellow-500 rounded hover:bg-yellow-600">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Visit link footer action --}}
    @if ($$module_name_singular->getFullUrl())
        <div class="mt-5 flex justify-end">
            <a
                href="{{ $$module_name_singular->getFullUrl() }}"
                target="_blank"
                class="inline-flex items-center gap-1.5 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700"
            >
                <i class="fas fa-external-link-alt"></i>
                {{ __('menu::text.visit_link') }}
            </a>
        </div>
    @endif

</x-cube::backend-layout-show>

@endsection
