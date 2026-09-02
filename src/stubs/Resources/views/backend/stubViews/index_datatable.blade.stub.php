@extends('backend.layouts.app')

@section('title') {{ __($module_action) }} {{ __($module_title) }} @endsection

@section('breadcrumbs')
<x-backend.breadcrumbs>
    <x-backend.breadcrumb-item type="active" icon='{{ $module_icon }}'>{{ __($module_title) }}</x-backend.breadcrumb-item>
</x-backend.breadcrumbs>
@endsection

@section('content')
{{-- This view is kept for compatibility. The primary index view (index.blade.php) uses --}}
{{-- server-side pagination with Tailwind/Flowbite. The index_data and index_list routes --}}
{{-- can be removed from routes/web.php if DataTable functionality is not needed. --}}
<div class="rounded-lg bg-white shadow dark:bg-gray-800">
    <div class="p-6">
        <x-backend.section-header :module_name="$module_name" :module_title="$module_title" :module_icon="$module_icon" :module_action="$module_action" />

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
                            <a href="{{ url("admin/$module_name", $module_name_singular->id) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-500">
                                {{ $module_name_singular->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4">{{ $module_name_singular->updated_at->diffForHumans() }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route("backend.$module_name.edit", $module_name_singular) }}"
                               class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 hover:text-blue-600 dark:text-gray-400 dark:hover:bg-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
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
