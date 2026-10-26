@extends("backend.layouts.app")

@section("title")
    {{ __($module_action) }} {{ __($module_title) }}
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
    <x-cube::backend-layout-show
        :data="$$module_name_singular"
        :module_name="$module_name"
        :module_path="$module_path"
        :module_title="$module_title"
        :module_icon="$module_icon"
        :module_action="$module_action"
    >
        <x-cube::backend-section-header
            :data="$$module_name_singular"
            :module_name="$module_name"
            :module_title="$module_title"
            :module_icon="$module_icon"
            :module_action="$module_action"
        />

        <div class="mt-4 flex flex-wrap gap-4">
            <div class="w-full sm:w-2/3">
                <x-cube::backend-section-show-table :data="$$module_name_singular" :module_name="$module_name" />
            </div>
            <div class="w-full sm:flex-1">
                <h5 class="mb-2 text-base font-semibold dark:text-gray-400">Category</h5>
                <ul class="list-inside list-disc space-y-1">
                    <li>
                        <x-cube::link href="{{ route('backend.categories.show', [$$module_name_singular->category_id, $$module_name_singular->category->slug]) }}">
                            {{ $$module_name_singular->category->name }}
                        </x-cube::link>
                    </li>
                </ul>

                <h5 class="mb-2 mt-4 text-base font-semibold dark:text-gray-400">
                    Tags
                    <small class="text-sm font-normal text-gray-500">({{ count($$module_name_singular->tags) }})</small>
                </h5>

                <ul class="list-inside list-disc space-y-1">
                    @foreach ($$module_name_singular->tags as $tag)
                        <li>
                            <x-cube::link href="{{ route('backend.tags.show', [$tag->id, $tag->slug]) }}">
                                {{ $tag->name }}
                            </x-cube::link>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </x-cube::backend-layout-show>
@endsection
