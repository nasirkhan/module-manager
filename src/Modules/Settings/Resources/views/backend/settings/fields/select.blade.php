<div class="mb-5">
    <x-cube::label :for="$field['name']" :required="Str::contains($field['rules'], 'required')">
        {{ __($field['label']) }}
        <span class="text-xs text-gray-500 dark:text-gray-400">({{ $field['name'] }})</span>
    </x-cube::label>
    <x-cube::select
        name="{{ $field['name'] }}"
        id="{{ $field['name'] }}"
        :required="Str::contains($field['rules'], 'required')"
        :class="Arr::get($field, 'class', '')"
    >
        @foreach (Arr::get($field, 'options', []) as $val => $label)
            <option @if (old($field['name'], setting($field['name'])) == $val) selected @endif value="{{ $val }}">
                {{ $label }}
            </option>
        @endforeach
    </x-cube::select>
    <x-cube::error :messages="$errors->get($field['name'])" />
</div>
