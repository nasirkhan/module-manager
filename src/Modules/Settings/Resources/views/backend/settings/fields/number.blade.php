<div class="mb-5">
    <x-cube::label :for="$field['name']" :required="Str::contains($field['rules'], 'required')">
        {{ __($field['label']) }}
        <span class="text-xs text-gray-500 dark:text-gray-400">({{ $field['name'] }})</span>
    </x-cube::label>
    <x-cube::input
        type="{{ $field['type'] }}"
        name="{{ $field['name'] }}"
        id="{{ $field['name'] }}"
        :value="old($field['name'], setting($field['name']))"
        :placeholder="__($field['label'])"
        :required="Str::contains($field['rules'], 'required')"
        :class="Arr::get($field, 'class', '')"
    />
    <x-cube::error :messages="$errors->get($field['name'])" />
</div>
