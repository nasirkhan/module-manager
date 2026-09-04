@extends('backend.layouts.app')

@section('title') {{ __($module_action) }} {{ __($module_title) }} @endsection

@section('breadcrumbs')
<x-cube::backend-breadcrumbs>
    <x-cube::backend-breadcrumb-item route='{{route("backend.$module_name.index")}}' icon='{{ $module_icon }}'>
        {{ __($module_title) }}
    </x-cube::backend-breadcrumb-item>
    <x-cube::backend-breadcrumb-item type="active">{{ __($module_action) }}</x-cube::backend-breadcrumb-item>
</x-cube::backend-breadcrumbs>
@endsection

@section('content')
<x-cube::backend-layout-show :data="${$module_name_singular}">
    <x-cube::backend-section-header>
        <i class="{{ $module_icon }} fa-fw"></i>
        {{ ${$module_name_singular}->name }}
        <small class="text-gray-500 dark:text-gray-400">{{ __($module_title) }}</small>

        <x-slot name="toolbar">
            <x-cube::backend-button-return-back :small="true" />
            <a
                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 m-0.5"
                href="{{ route("backend.$module_name.index") }}"
                title="{{ __('menu::text.menu_list') }}"
            >
                <i class="fas fa-list"></i>
                {{ __('menu::text.menu_list') }}
            </a>
            <a
                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 m-0.5"
                href="{{ route('backend.menuitems.create', ['menu_id' => ${$module_name_singular}->id]) }}"
                title="{{ __('menu::text.add_menu_item') }}"
            >
                <i class="fas fa-plus-circle"></i>
                {{ __('menu::text.add_menu_item') }}
            </a>
            <x-cube::backend-button-edit
                title="{{ __('Edit') }} {{ __($module_title) }}"
                route='{!! route("backend.$module_name.edit", ${$module_name_singular}) !!}'
                :small="true"
            />
        </x-slot>
    </x-cube::backend-section-header>

    <dl class="grid grid-cols-2 sm:grid-cols-4 gap-x-6 gap-y-4 text-sm py-2">
        <div>
            <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide">{{ __('menu::text.name') }}</dt>
            <dd class="mt-0.5 text-gray-900 dark:text-gray-100">{{ ${$module_name_singular}->name }}</dd>
        </div>
        <div>
            <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide">{{ __('menu::text.location') }}</dt>
            <dd class="mt-0.5 text-gray-900 dark:text-gray-100">{{ ${$module_name_singular}->location }}</dd>
        </div>
        <div>
            <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide">{{ __('menu::text.locale') }}</dt>
            <dd class="mt-0.5 text-gray-900 dark:text-gray-100">{{ ${$module_name_singular}->locale ?? __('menu::text.all_locales') }}</dd>
        </div>
        <div>
            <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide">{{ __('menu::text.status') }}</dt>
            <dd class="mt-1 flex flex-wrap gap-1">
                @if(${$module_name_singular}->is_active)
                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-green-600 rounded">{{ __('menu::text.active') }}</span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-yellow-500 rounded">{{ __('menu::text.inactive') }}</span>
                @endif
                @if(${$module_name_singular}->is_visible)
                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-blue-600 rounded">{{ __('menu::text.visible') }}</span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-gray-500 rounded">{{ __('menu::text.hidden') }}</span>
                @endif
                @if(${$module_name_singular}->is_public)
                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-sky-500 rounded">{{ __('menu::text.public') }}</span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-gray-700 rounded">{{ __('menu::text.private') }}</span>
                @endif
            </dd>
        </div>
        @if(${$module_name_singular}->description)
        <div class="col-span-2 sm:col-span-4">
            <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide">{{ __('menu::text.description') }}</dt>
            <dd class="mt-0.5 text-gray-900 dark:text-gray-100">{{ ${$module_name_singular}->description }}</dd>
        </div>
        @endif
    </dl>

    <hr class="border-gray-200 dark:border-gray-700">

    <div class="mt-4">
        <div class="flex items-center justify-between mb-3">
            <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide flex items-center gap-2">
                <i class="fas fa-list"></i>
                {{ __('menu::text.menu_items') }}
                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-blue-600 rounded">
                    {{ ${$module_name_singular}->allItems->count() }}
                </span>
            </h5>
            <a
                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-green-600 rounded-lg hover:bg-green-700"
                href="{{ route('backend.menuitems.create', ['menu_id' => ${$module_name_singular}->id]) }}"
            >
                <i class="fas fa-plus-circle mr-1"></i>
                {{ __('menu::text.add_item') }}
            </a>
        </div>

        @if(${$module_name_singular}->items->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 border-collapse">
                    <thead>
                        <tr class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <th class="px-4 py-3">{{ __('menu::text.order') }}</th>
                            <th class="px-4 py-3">{{ __('menu::text.name') }}</th>
                            <th class="px-4 py-3">{{ __('menu::text.type') }}</th>
                            <th class="px-4 py-3">{{ __('menu::text.url_route') }}</th>
                            <th class="px-4 py-3">{{ __('menu::text.status') }}</th>
                            <th class="px-4 py-3 text-center">{{ __('menu::text.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(${$module_name_singular}->items->sortBy('sort_order') as $item)
                            @include('menu::backend.menus.partials.menu-item-row', ['item' => $item, 'level' => 0])
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="flex items-center gap-2 p-4 text-sm text-blue-700 bg-blue-50 rounded-lg dark:bg-blue-900/20 dark:text-blue-400">
                <i class="fas fa-info-circle"></i>
                {{ __('menu::text.no_menu_items') }}
                <a href="{{ route('backend.menuitems.create', ['menu_id' => ${$module_name_singular}->id]) }}" class="font-medium underline hover:no-underline">
                    {{ __('menu::text.add_first_item') }}
                </a>
            </div>
        @endif
    </div>
</x-cube::backend-layout-show>
@endsection
