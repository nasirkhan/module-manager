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
                            <th class="px-4 py-3">@lang("File")</th>
                            <th class="px-4 py-3">@lang("Size")</th>
                            <th class="px-4 py-3">@lang("Date")</th>
                            <th class="px-4 py-3">@lang("Age")</th>
                            <th class="px-4 py-3 text-right">@lang("Action")</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($backups as $key => $backup)
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-4 py-3 text-gray-900 dark:text-white">
                                    {{ ++$key }}
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                    {{ $backup["file_name"] }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $backup["file_size"] }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $backup["date_created"] }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $backup["date_ago"] }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1 flex-wrap">
                                        @can("download_" . $module_name)
                                            <a
                                                href="{{ route("backend.$module_name.download", $backup["file_name"]) }}"
                                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700"
                                                title="@lang("Download File")"
                                            >
                                                <i class="fas fa-download fa-fw"></i>
                                            </a>
                                        @endcan

                                        @can("delete_" . $module_name)
                                            <a
                                                href="{{ route("backend.$module_name.delete", $backup["file_name"]) }}"
                                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700"
                                                title="@lang("Delete File")"
                                                onclick="return confirm('@lang("Are you sure you want to delete this backup file?")')"
                                            >
                                                <i class="fas fa-trash fa-fw"></i>
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-400 dark:text-gray-500">
                                    @lang("No backup has been created yet!")
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="border-t border-zinc-200 dark:border-zinc-700 px-6 py-3"></div>
    </div>
@endsection
