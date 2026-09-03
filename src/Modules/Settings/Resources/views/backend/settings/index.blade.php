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

            <div class="mt-6">
                <form method="POST" action="{{ route("backend.$module_name.store") }}">
                @csrf

                @if (count(config("settings.setting_fields", [])))
                    @foreach (config("settings.setting_fields") as $section => $fields)
                        <div class="bg-zinc-50 dark:bg-slate-800 rounded-lg border border-zinc-200 dark:border-zinc-600 mb-6">
                            <div class="flex items-center gap-2 px-6 py-4 border-b border-zinc-200 dark:border-zinc-600">
                                <i class="{{ Arr::get($fields, 'icon', 'fas fa-cog') }} text-zinc-500 dark:text-zinc-400"></i>
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ $fields["title"] }}</h3>
                            </div>
                            @if (!empty($fields["desc"]))
                                <div class="px-6 pt-4 pb-0">
                                    <p class="text-sm text-gray-500 dark:text-zinc-400">{{ $fields["desc"] }}</p>
                                </div>
                            @endif
                            <div class="p-6">
                                @foreach ($fields["elements"] as $field)
                                    @includeIf("settings::backend.settings.fields." . $field["type"])
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif

                <div class="flex items-center mt-2">
                    <x-cube::backend-button-save />
                </div>

                </form>
            </div>
        </div>
        <div class="border-t border-zinc-200 dark:border-zinc-700 px-6 py-3"></div>
    </div>
@endsection
