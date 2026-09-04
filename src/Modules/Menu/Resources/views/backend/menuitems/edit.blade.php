@extends("backend.layouts.app")

@section("title")
    {{ __($module_action) }} {{ __($module_title) }}
@endsection

@section("breadcrumbs")
    <x-cube::backend-breadcrumbs>
        <x-cube::backend-breadcrumb-item route='{{ route("backend.menus.index") }}' icon="fa-solid fa-list">
            {{ __('Menus') }}
        </x-cube::backend-breadcrumb-item>
        @if ($$module_name_singular->menu_id)
            <x-cube::backend-breadcrumb-item route='{{ route("backend.menus.show", $$module_name_singular->menu_id) }}' icon="fa-solid fa-list">
                {{ __('menu::text.menu_list') }}
            </x-cube::backend-breadcrumb-item>
        @endif
        <x-cube::backend-breadcrumb-item type="active">{{ __($module_action) }} {{ __($module_title) }}</x-cube::backend-breadcrumb-item>
    </x-cube::backend-breadcrumbs>
@endsection

@section("content")
@php $data = $$module_name_singular; @endphp

<x-cube::backend-layout-edit :data="$data">

    <x-cube::backend-section-header>
        <i class="{{ $module_icon }} fa-fw"></i>
        {{ $data->name }}
        <small class="text-gray-500 dark:text-gray-400">{{ __($module_action) }}</small>

        <x-slot name="toolbar">
            <x-cube::backend-button-return-back :small="true" />
            <x-cube::backend-button-show
                title="{{ __('Show') }} {{ __($module_title) }}"
                route='{!! route("backend.menuitems.show", $data) !!}'
                :small="true"
            />
        </x-slot>
    </x-cube::backend-section-header>

    @livewire("menu.menu-item-component", ["menuItem" => $data])

</x-cube::backend-layout-edit>
@endsection
