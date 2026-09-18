<div class="mb-5">
    <x-cube::label :for="$field['name']" :required="Str::contains($field['rules'], 'required')">
        {{ __($field['label']) }}
        <span class="text-xs text-gray-500 dark:text-gray-400">({{ $field['name'] }})</span>
    </x-cube::label>
    <x-cube::textarea
        name="{{ $field['name'] }}"
        id="{{ $field['name'] }}"
        :placeholder="__($field['label'])"
        :required="Str::contains($field['rules'], 'required')"
        :class="Arr::get($field, 'class', '')"
        rows="6"
    >@if (isset($field['display']) && $field['display'] == 'raw'){!! old($field['name'], setting($field['name'])) !!}@else{{ old($field['name'], setting($field['name'])) }}@endif</x-cube::textarea>
    @if (isset($field['help']))
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $field['help'] }}</p>
    @endif
    <x-cube::error :messages="$errors->get($field['name'])" />
</div>
