@extends("frontend.layouts.app")

@section("title")
    {{ __($module_title) }}
@endsection

@section("content")
    <x-cube::header-block :title="__($module_title)" />

    <section class="bg-white py-10 text-gray-600 dark:bg-gray-700 sm:py-16">
        <div class="mx-auto max-w-7xl px-6 sm:px-10">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($$module_name as $$module_name_singular)
                    @php
                        $details_url = route("frontend.$module_name.show", [
                            encode_id($$module_name_singular->id),
                            $$module_name_singular->slug,
                        ]);
                    @endphp

                    <x-cube::card :url="$details_url" :name="$$module_name_singular->name">
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                            {{ $$module_name_singular->description }}
                        </p>
                    </x-cube::card>
                @endforeach
            </div>
            <div class="mt-8 w-full">
                {{ $$module_name->links() }}
            </div>
        </div>
    </section>
@endsection
