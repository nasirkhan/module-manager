@extends("backend.layouts.app")

@section("title")
    {{ __($module_action) }} {{ __($module_title) }}
@endsection

@section("breadcrumbs")
    <x-cube::backend-breadcrumbs>
        <x-cube::backend-breadcrumb-item type="active" icon="{{ $module_icon }}">
            {{ __($module_title) }}
        </x-cube::backend-breadcrumb-item>
    </x-cube::backend-breadcrumbs>
@endsection

@section("content")
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6">
            <x-cube::backend-section-header
                :module_name="$module_name"
                :module_title="$module_title"
                :module_icon="$module_icon"
                :module_action="$module_action"
            />

            <div class="overflow-x-auto mt-4">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">@lang("tag::text.name")</th>
                            <th class="px-4 py-3">@lang("tag::text.slug")</th>
                            <th class="px-4 py-3">@lang("tag::text.updated_at")</th>
                            <th class="px-4 py-3">@lang("tag::text.created_by")</th>
                            <th class="px-4 py-3 text-right">@lang("tag::text.action")</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($$module_name as $module_name_singular)
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-4 py-3 text-gray-900 dark:text-white">
                                    {{ $module_name_singular->id }}
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                    <a
                                        href="{{ url("admin/$module_name", $module_name_singular->id) }}"
                                        wire:navigate
                                        class="hover:text-blue-600 dark:hover:text-blue-400"
                                    >{{ $module_name_singular->name }}</a>
                                </td>
                                <td class="px-4 py-3">{{ $module_name_singular->slug }}</td>
                                <td class="px-4 py-3">{{ $module_name_singular->updated_at->diffForHumans() }}</td>
                                <td class="px-4 py-3">{{ $module_name_singular->created_by }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1 flex-wrap">
                                        <a
                                            href="{!! route("backend.$module_name.edit", $module_name_singular) !!}"
                                            wire:navigate
                                            class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700"
                                            title="Edit {{ ucwords(Str::singular($module_name)) }}"
                                        ><i class="fas fa-wrench fa-fw"></i></a>
                                        <a
                                            href="{!! route("backend.$module_name.show", $module_name_singular) !!}"
                                            wire:navigate
                                            class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-white bg-green-600 rounded-lg hover:bg-green-700"
                                            title="Show {{ ucwords(Str::singular($module_name)) }}"
                                        ><i class="fas fa-desktop fa-fw"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-400 dark:text-gray-500">
                                    @lang("No tags found.")
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-3">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Total {{ $$module_name->total() }} {{ ucwords($module_name) }}
                </div>
                <div>
                    {!! $$module_name->render() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
