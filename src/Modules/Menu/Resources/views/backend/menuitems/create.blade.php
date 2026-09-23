@extends("backend.layouts.app")

@section("title")
    {{ __($module_action) }} {{ __($module_title) }}
@endsection

@section("breadcrumbs")
    <x-cube::backend-breadcrumbs>
        <x-cube::backend-breadcrumb-item route='{{route("backend.menus.index")}}' icon="fa-solid fa-list">
            {{ __('Menus') }}
        </x-cube::backend-breadcrumb-item>
        @if(request('menu_id'))
            <x-cube::backend-breadcrumb-item route='{{route("backend.menus.show", request("menu_id"))}}' icon="{{ $module_icon }}">
                {{ __('Menu Details') }}
            </x-cube::backend-breadcrumb-item>
        @endif
        <x-cube::backend-breadcrumb-item type="active">{{ __($module_action) }} {{ __($module_title) }}</x-cube::backend-breadcrumb-item>
    </x-cube::backend-breadcrumbs>
@endsection

@section("content")
    <div class="rounded-lg bg-white shadow dark:bg-gray-800">
        <div class="p-6">
            <x-cube::backend-section-header
                :module_name="$module_name"
                :module_title="$module_title"
                :module_icon="$module_icon"
                :module_action="$module_action"
            />

            <div class="mt-4">
                @livewire('menu.menu-item-component', ['menu_id' => request('menu_id')])
            </div>
        </div>
    </div>
@endsection
