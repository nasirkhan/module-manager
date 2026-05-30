@extends("frontend.layouts.app")

@section("title")
    {{ $$module_name_singular->name }}
@endsection

@section("content")
    @php
        $shareUrl = route("frontend.posts.show", [encode_id($$module_name_singular->id), $$module_name_singular->slug]);
        $shareDescription = $$module_name_singular->meta_description ?: $$module_name_singular->intro;
        $shareImage = $$module_name_singular->meta_og_image ?: $$module_name_singular->image;

        if ($shareImage && ! \Illuminate\Support\Str::startsWith($shareImage, ["http://", "https://"])) {
            $shareImage = asset($shareImage);
        }
    @endphp

    <section class="body-font bg-gray-100 px-6 text-gray-600 sm:px-20 dark:bg-gray-800 dark:text-gray-400">
        <div class="container mx-auto flex flex-col items-center py-8 sm:py-16 md:flex-row">
            <div
                class="flex flex-col items-center text-center sm:w-4/12 md:items-start md:pr-16 md:text-left lg:flex-grow lg:pr-24"
            >
                <h1 class="mb-4 text-3xl font-medium text-gray-800 sm:text-4xl dark:text-gray-200">
                    {{ $$module_name_singular->name }}
                </h1>
                @if ($$module_name_singular->intro != "")
                    <p class="mb-8 leading-relaxed">
                        {{ $$module_name_singular->intro }}
                    </p>
                @endif

                @include("frontend.includes.messages")
            </div>
            <div class="mb-4 w-full sm:mb-0 sm:w-8/12">
                <img
                    class="rounded object-cover object-center shadow-md"
                    src="{{ $$module_name_singular->image }}"
                    alt="{{ $$module_name_singular->name }}"
                />
            </div>
        </div>
    </section>

    <section class="px-6 py-6 sm:px-20 sm:py-10 dark:bg-gray-700 dark:text-gray-300">
        <div class="container mx-auto flex flex-col md:flex-row">
            <div class="flex flex-col sm:w-8/12 sm:pr-8 lg:flex-grow">
                <div class="pb-5">
                    <p>
                        {!! $$module_name_singular->content !!}
                    </p>
                </div>

                <hr />

                <div class="py-5">
                    <div class="flex flex-col justify-between sm:flex-row">
                        <div class="pb-2">
                            {{ __("Written by") }}:
                            {{ isset($$module_name_singular->created_by_alias) ? $$module_name_singular->created_by_alias : $$module_name_singular->created_by_name }}
                        </div>
                        <div class="pb-2">
                            {{ __("Published at") }}: {{ $$module_name_singular->published_at->isoFormat("llll") }}
                        </div>
                    </div>
                </div>

                <div class="flex flex-row justify-between py-5">
                    <div>
                        <span class="font-weight-bold">
                            @lang("Category")
                            :
                        </span>
                        <x-cube::badge
                            :url="route('frontend.categories.show', [
                                encode_id($$module_name_singular->category_id),
                                $$module_name_singular->category->slug,
                            ])"
                            :text="$$module_name_singular->category->name"
                        />
                    </div>
                </div>

                @if (count($$module_name_singular->tags))
                    <div class="py-5">
                        <span class="font-weight-bold">
                            @lang("Tags")
                            :
                        </span>

                        @foreach ($$module_name_singular->tags as $tag)
                            <x-cube::badge
                                :url="route('frontend.tags.show', [encode_id($tag->id), $tag->slug])"
                                :text="$tag->name"
                            />
                        @endforeach
                    </div>
                @endif

                <div class="py-5">
                    <x-sharekit::buttons
                        theme="tailwind"
                        label="{{ __('Share with others') }}"
                        :url="$shareUrl"
                        :title="$$module_name_singular->name"
                        :description="$shareDescription"
                        :image="$shareImage"
                        :networks="['x', 'facebook', 'linkedin', 'whatsapp', 'telegram', 'email', 'copy', 'native']"
                        size="sm"
                        :show-heading="true"
                    />
                </div>

                <div class="py-5">
                    
                </div>
            </div>

            <div class="flex flex-col sm:w-4/12">
                <div class="py-5 sm:pt-0">
                    <livewire:post.frontend-recent-posts />
                </div>
            </div>
        </div>
    </section>
@endsection
