@extends('backend.layouts.app')

@section('title') {{ __($module_action) }} {{ __($module_title) }} @endsection

@section('breadcrumbs')
<x-cube::backend-breadcrumbs>
    <x-cube::backend-breadcrumb-item type="active" icon='{{ $module_icon }}'>{{ __($module_title) }}</x-cube::backend-breadcrumb-item>
</x-cube::backend-breadcrumbs>
@endsection

@section('content')
{{-- This view is kept for compatibility. The primary index view (index.blade.php) uses --}}
{{-- server-side pagination with Tailwind/Flowbite. The index_data and index_list routes --}}
{{-- can be removed from routes/web.php if DataTable functionality is not needed. --}}
<div class="rounded-lg bg-white shadow dark:bg-gray-800">
    <div class="p-6">
        <x-cube::backend-section-header :module_name="$module_name" :module_title="$module_title" :module_icon="$module_icon" :module_action="$module_action" />

        <div class="mt-6 overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">#</th>
                        <th scope="col" class="px-6 py-3">@lang("{{moduleNameLower}}::text.name")</th>
                        <th scope="col" class="px-6 py-3">@lang("{{moduleNameLower}}::text.updated_at")</th>
                        <th scope="col" class="px-6 py-3 text-right">@lang("{{moduleNameLower}}::text.action")</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($$module_name as $module_name_singular)
                    <tr class="border-b bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $module_name_singular->id }}</td>
                        <td class="px-6 py-4">
                            <x-cube::link href="{{ url('admin/'.$module_name, $module_name_singular->id) }}">
                                {{ $module_name_singular->name }}
                            </x-cube::link>
                        </td>
                        <td class="px-6 py-4">{{ $module_name_singular->updated_at->diffForHumans() }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <x-cube::backend-button-edit
                                    :route="route('backend.'.$module_name.'.edit', $module_name_singular)"
                                    :title="__('Edit') . ' ' . ucwords(Str::singular($module_name))"
                                    small="true"
                                />
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex items-center justify-between border-t border-gray-200 px-6 py-4 dark:border-gray-700">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('Total') }} {{ $$module_name->total() }} {{ ucwords($module_name) }}
        </p>
        <div>{!! $$module_name->links() !!}</div>
    </div>
</div>
@endsection
