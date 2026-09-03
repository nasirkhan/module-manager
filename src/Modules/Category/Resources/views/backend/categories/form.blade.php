<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
    <x-cube::group name="name" :label="label_case('name')" required>
        <x-cube::input type="text" name="name" :value="old('name', optional($data)->name ?? '')" :placeholder="label_case('name')" required />
    </x-cube::group>
    <x-cube::group name="slug" :label="label_case('slug')">
        <x-cube::input type="text" name="slug" :value="old('slug', optional($data)->slug ?? '')" :placeholder="label_case('slug')" />
    </x-cube::group>
    <x-cube::group name="group_name" :label="label_case('group_name')">
        <x-cube::input type="text" name="group_name" :value="old('group_name', optional($data)->group_name ?? '')" :placeholder="label_case('group_name')" />
    </x-cube::group>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
    <div class="sm:col-span-2">
        <x-cube::group name="image" :label="label_case('image')">
            <x-cube::file-input name="image" accept="image/*" />
        </x-cube::group>
    </div>

    @if ($data && $data->getMedia($module_name)->first())
        <div>
            <figure class="figure">
                <a
                    href="{{ asset($data->image) }}"
                    data-lightbox="image-set"
                    data-title="Path: {{ asset($data->image) }}"
                >
                    <img
                        src="{{ asset($data->getMedia($module_name)->first()->getUrl('thumb300')) }}"
                        class="figure-img img-fluid img-thumbnail rounded"
                        alt=""
                    />
                </a>
            </figure>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="image_remove" id="image_remove" name="image_remove" />
                <label class="form-check-label" for="image_remove">Remove this image</label>
            </div>
        </div>
        <x-library.lightbox />
    @endif
</div>

<div class="mb-4">
    <x-cube::group name="description" :label="label_case('description')">
        <x-cube::textarea name="description" :placeholder="label_case('description')">{{ old('description', optional($data)->description ?? '') }}</x-cube::textarea>
    </x-cube::group>
</div>

<hr class="my-4" />

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
    <x-cube::group name="meta_title" :label="label_case('meta_title')">
        <x-cube::input type="text" name="meta_title" :value="old('meta_title', optional($data)->meta_title ?? '')" :placeholder="label_case('meta_title')" />
    </x-cube::group>
    <x-cube::group name="meta_keyword" :label="label_case('meta_keyword')">
        <x-cube::input type="text" name="meta_keyword" :value="old('meta_keyword', optional($data)->meta_keyword ?? '')" :placeholder="label_case('meta_keyword')" />
    </x-cube::group>
    <x-cube::group name="meta_description" :label="label_case('meta_description')">
        <x-cube::input type="text" name="meta_description" :value="old('meta_description', optional($data)->meta_description ?? '')" :placeholder="label_case('meta_description')" />
    </x-cube::group>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
    <x-cube::group name="status" :label="label_case('status')" required>
        <x-cube::select name="status" required>
            <option value="">-- Select an option --</option>
            @foreach(\Nasirkhan\ModuleManager\Modules\Category\Enums\CategoryStatus::toArray() as $optionKey => $optionValue)
                <option value="{{ $optionKey }}" @selected(old('status', optional($data)->status?->value ?? '') == $optionKey)>{{ $optionValue }}</option>
            @endforeach
        </x-cube::select>
    </x-cube::group>
</div>
