@extends("backend.layouts.app")

@section("title")
        {{ $$module_name_singular->name }} - {{ __($module_action) }} {{ __($module_title) }}
@endsection

@section("breadcrumbs")
    <x-backend.breadcrumbs>
        <x-backend.breadcrumb-item route='{{ route("backend.$module_name.index") }}' icon="{{ $module_icon }}">
            {{ __($module_title) }}
        </x-backend.breadcrumb-item>
        <x-backend.breadcrumb-item type="active">{{ __($module_action) }}</x-backend.breadcrumb-item>
    </x-backend.breadcrumbs>
@endsection

@section("content")
    <x-backend.layouts.show
        :data="$$module_name_singular"
        :module_name="$module_name"
        :module_path="$module_path"
        :module_title="$module_title"
        :module_icon="$module_icon"
        :module_action="$module_action"
    >
        <x-backend.section-header
            :data="$$module_name_singular"
            :module_name="$module_name"
            :module_title="$module_title"
            :module_icon="$module_icon"
            :module_action="$module_action"
        />

        <div class="flex flex-wrap mt-4 gap-4">
            <div class="w-full sm:w-1/2">
                <x-backend.section-show-table :data="$$module_name_singular" :module_name="$module_name" />
            </div>
            <div class="w-full sm:flex-1">
                <h5 class="text-base font-semibold mb-2 dark:text-gray-400">
                    Posts
                    <small class="text-sm font-normal text-gray-500">({{ count($posts) }})</small>
                </h5>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($posts as $post)
                        <li>
                            <a href="{{ route("backend.posts.show", [$post->id, $post->slug]) }}" class="text-blue-600 hover:underline dark:text-blue-400">
                                {{ $post->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </x-backend.layouts.show>
@endsection
