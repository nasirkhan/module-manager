<div class="grid grid-cols-1 sm:grid-cols-12 gap-4 mb-4">
    <div class="sm:col-span-5">
        <x-cube::group name="name" :label="__('post::posts.name')" required>
            <x-cube::input type="text" name="name" :value="old('name', optional($data)->name ?? '')" :placeholder="__('post::posts.name')" required />
        </x-cube::group>
    </div>
    <div class="sm:col-span-3">
        <x-cube::group name="slug" :label="__('post::posts.slug')">
            <x-cube::input type="text" name="slug" :value="old('slug', optional($data)->slug ?? '')" :placeholder="__('post::posts.slug')" />
        </x-cube::group>
    </div>
    <div class="sm:col-span-4">
        <x-cube::group name="created_by_alias" :label="__('post::posts.created_by_alias')">
            <x-cube::input type="text" name="created_by_alias" :value="old('created_by_alias', optional($data)->created_by_alias ?? '')" placeholder="Hide Author User's Name and use Alias" />
        </x-cube::group>
    </div>
</div>

<div class="mb-4">
    <x-cube::group name="intro" :label="__('post::posts.intro')" required>
        <x-cube::textarea name="intro" :placeholder="__('post::posts.intro')" required>{{ old('intro', optional($data)->intro ?? '') }}</x-cube::textarea>
    </x-cube::group>
</div>

<div class="mb-4">
    <x-cube::group name="content" :label="__('post::posts.content')" required>
        <x-jodit::editor
            name="content"
            id="content"
            :value="old('content', optional($data)->content ?? '')"
            :placeholder="__('post::posts.content')"
            :required="true"
        />
    </x-cube::group>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
    <div class="sm:col-span-2">
        <x-cube::group name="image" :label="label_case('image')">
            <x-cube::file-input name="image" accept="image/*" />
        </x-cube::group>
    </div>

    @if (isset($$module_name_singular) && $$module_name_singular->getMedia($module_name)->first())
        <div>
            <figure class="figure">
                <a
                    href="{{ asset($$module_name_singular->image) }}"
                    data-lightbox="image-set"
                    data-title="Path: {{ asset($$module_name_singular->image) }}"
                >
                    <img
                        src="{{ asset($$module_name_singular->getMedia($module_name)->first()->getUrl('thumb300')) }}"
                        class="figure-img img-fluid img-thumbnail rounded"
                        alt=""
                    />
                </a>
            </figure>
        </div>
        <x-library.lightbox />
    @endif
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
    @php
        $categoryOptions = \Nasirkhan\ModuleManager\Modules\Category\Models\Category::active()->orderBy('name')->pluck('name', 'id');
        $selectedCategories = ! empty($data) ? [$data->category_id] : [];
    @endphp
    <x-cube::group name="category_id" :label="__('post::posts.category_id')" required>
        <x-cube::select name="category_id" required>
            <option value="">{{ __('Select an option') }}</option>
            @foreach($categoryOptions as $catId => $catName)
                <option value="{{ $catId }}" @selected(old('category_id', '') == $catId || in_array($catId, $selectedCategories))>{{ $catName }}</option>
            @endforeach
        </x-cube::select>
    </x-cube::group>
    <x-cube::group name="type" :label="__('post::posts.type')" required>
        <x-cube::select name="type" required>
            <option value="">{{ __('Select an option') }}</option>
            @foreach(\Nasirkhan\ModuleManager\Modules\Post\Enums\PostType::toArray() as $typeKey => $typeLabel)
                <option value="{{ $typeKey }}" @selected(old('type', optional($data)->type?->value ?? '') == $typeKey)>{{ $typeLabel }}</option>
            @endforeach
        </x-cube::select>
    </x-cube::group>
    <x-cube::group name="is_featured" :label="__('post::posts.is_featured')" required>
        <x-cube::select name="is_featured" required>
            <option value="">{{ __('Select an option') }}</option>
            @foreach(['0' => 'No', '1' => 'Yes'] as $featuredValue => $featuredLabel)
                <option value="{{ $featuredValue }}" @selected(old('is_featured', optional($data)->is_featured ?? '') == $featuredValue)>{{ $featuredLabel }}</option>
            @endforeach
        </x-cube::select>
    </x-cube::group>
</div>

<div class="mb-4">
    @php
        $tagOptions = \Nasirkhan\ModuleManager\Modules\Tag\Models\Tag::active()->orderBy('name')->pluck('name', 'id');
        $selectedTags = ! empty($data) ? optional($data->tags)->pluck('id')->toArray() : [];
    @endphp
    <x-cube::group name="tags_list" :label="__('post::posts.tags')">
        <x-cube::tom-select name="tags_list[]" multiple>
            @foreach($tagOptions as $tagId => $tagName)
                <option value="{{ $tagId }}" @selected(in_array($tagId, (array) old('tags_list', $selectedTags)))>{{ $tagName }}</option>
            @endforeach
        </x-cube::tom-select>
    </x-cube::group>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
    <x-cube::group name="status" :label="__('post::posts.status')" required>
        <x-cube::select name="status" required>
            <option value="">{{ __('Select an option') }}</option>
            @foreach(\Nasirkhan\ModuleManager\Modules\Post\Enums\PostStatus::toArray() as $statusKey => $statusLabel)
                <option value="{{ $statusKey }}" @selected(old('status', optional($data)->status?->value ?? '') == $statusKey)>{{ $statusLabel }}</option>
            @endforeach
        </x-cube::select>
    </x-cube::group>
    <x-cube::group name="published_at" :label="__('post::posts.published_at')" required>
        <x-cube::input type="datetime-local" name="published_at" :value="old('published_at', optional($data)->published_at?->format('Y-m-d\TH:i') ?? '')" required />
    </x-cube::group>
</div>

<div class="grid grid-cols-1 sm:grid-cols-12 gap-4 mb-4">
    <div class="sm:col-span-5">
        <x-cube::group name="meta_title" :label="__('post::posts.meta_title')">
            <x-cube::input type="text" name="meta_title" :value="old('meta_title', optional($data)->meta_title ?? '')" :placeholder="__('post::posts.meta_title')" />
        </x-cube::group>
    </div>
    <div class="sm:col-span-5">
        <x-cube::group name="meta_keywords" :label="__('post::posts.meta_keywords')">
            <x-cube::input type="text" name="meta_keywords" :value="old('meta_keywords', optional($data)->meta_keywords ?? '')" :placeholder="__('post::posts.meta_keywords')" />
        </x-cube::group>
    </div>
    <div class="sm:col-span-2">
        <x-cube::group name="order" :label="__('post::posts.order')">
            <x-cube::input type="text" name="order" :value="old('order', optional($data)->order ?? '')" :placeholder="__('post::posts.order')" />
        </x-cube::group>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
    <x-cube::group name="meta_description" :label="__('post::posts.meta_description')">
        <x-cube::input type="text" name="meta_description" :value="old('meta_description', optional($data)->meta_description ?? '')" :placeholder="__('post::posts.meta_description')" />
    </x-cube::group>
    <x-cube::group name="meta_og_image" :label="__('post::posts.meta_og_image')">
        <x-cube::input type="text" name="meta_og_image" :value="old('meta_og_image', optional($data)->meta_og_image ?? '')" :placeholder="__('post::posts.meta_og_image')" />
    </x-cube::group>
</div>
